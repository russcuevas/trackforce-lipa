<?php

namespace App\Http\Controllers;

use App\Models\AuditTrailLog;
use App\Models\InvestigatorNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function ReportPage()
    {
        return view('report');
    }

    public function VerifyOtpPage(Request $request)
    {
        $request->validate([
            'report_number' => 'required|string|max:30',
        ]);

        return view('report_verify', [
            'reportNumber' => $request->query('report_number'),
        ]);
    }

    public function CreateReportPage(Request $request)
    {
        // ✅ Validation
        $validator = Validator::make($request->all(), [
            'incident_type' => 'required',
            'incident_type_other' => 'required_if:incident_type,Other|nullable|string|max:100',
            'location_name' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'reporter_email' => 'required|email',
            'evidence.*' => 'file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:10240', // only images/videos, max 10MB
        ], [
            'incident_type_other.required_if' => 'Please specify the incident type when selecting Other.',
            'evidence.*.mimes' => 'The evidence image/video field must be a file of type: jpg, jpeg, png, gif, mp4, mov, or avi.',
            'evidence.*.max' => 'Each evidence file must not exceed 10MB in size.',
        ]);

        if ($validator->fails()) {
            // Return JSON with 422 status for AJAX
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // ✅ Generate Report Number
        $year = date('Y');
        $count = DB::table('incidents')->whereYear('created_at', $year)->count() + 1;
        $reportNumber = 'TFL-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        // ✅ Generate OTP
        $otp = rand(100000, 999999);

        $incidentType = $request->incident_type === 'Other'
            ? $request->incident_type_other
            : $request->incident_type;

        // ✅ Insert Incident
        $incidentId = DB::table('incidents')->insertGetId([
            'report_number' => $reportNumber,
            'incident_type' => $incidentType,
            'location_name' => $request->location_name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'road_condition' => $request->road_condition,
            'weather_condition' => $request->weather_condition,
            'reporter_name' => $request->reporter_name,
            'reporter_contact' => $request->reporter_contact,
            'reporter_email' => $request->reporter_email,
            'reporter_address' => $request->reporter_address,
            'status' => 'Pending',
            'otp' => $otp,
            'is_verified' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // ✅ Insert Vehicles if provided
        if ($request->vehicle_type) {
            foreach ($request->vehicle_type as $index => $type) {
                $vehicleType = $type === 'Other' ? $request->vehicle_type_other[$index] : $type;
                $plate = $request->plate_number[$index] ?? null;
                $color = $request->vehicle_color[$index] ?? null; // new color field

                DB::table('vehicles')->insert([
                    'incident_id' => $incidentId,
                    'vehicle_type' => $vehicleType,
                    'plate_number' => $plate,
                    'color' => $color,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // ✅ Insert Involved Party (even if NULL fields)
        DB::table('involved_parties')->insert([
            'incident_id' => $incidentId,
            'full_name' => null,
            'age' => null,
            'sex' => null,
            'role' => null,
            'license_number' => null,
            'injury_severity' => null,
            'statement' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ✅ Upload Evidence
        if ($request->hasFile('evidence')) {
            foreach ($request->file('evidence') as $file) {
                $filename = $file->hashName(); // unique name
                $file->storeAs('evidence', $filename, 'public');

                DB::table('incident_evidence')->insert([
                    'incident_id' => $incidentId,
                    'file_path' => $filename,
                    'file_type' => $file->getClientMimeType(),
                    'uploaded_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        try {
            $this->sendOtpEmail($request->reporter_email, $reportNumber, (string) $otp);
        } catch (\Throwable $exception) {
            Log::error('Failed to send report OTP email.', [
                'report_number' => $reportNumber,
                'reporter_email' => $request->reporter_email,
                'error' => $exception->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Report saved but OTP email could not be sent. Please try again later.',
            ], 500);
        }

        AuditTrailLog::record([
            'incident_id' => $incidentId,
            'action_type' => 'public_report',
            'action_performed' => 'Public incident report submitted and waiting for OTP verification.',
        ]);

        // ✅ Return success JSON for AJAX
        return response()->json([
            'success' => true,
            'report_number' => $reportNumber,
            'reporter_email' => $request->reporter_email,
            'verify_url' => route('report.verify.page', ['report_number' => $reportNumber]),
        ]);
    }

    public function VerifyOtpRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_number' => 'required|string|max:30',
            'reporter_email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $incident = DB::table('incidents')
            ->select('id', 'report_number', 'incident_type', 'location_name', 'otp', 'is_verified')
            ->where('report_number', $request->report_number)
            ->where('reporter_email', $request->reporter_email)
            ->first();

        if (!$incident) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'errors' => ['report_number' => ['Report number and email do not match any submission.']],
                ], 422);
            }

            return redirect()
                ->back()
                ->withErrors(['report_number' => 'Report number and email do not match any submission.'])
                ->withInput();
        }

        if ((int) $incident->is_verified === 1) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'This report is already verified.',
                ]);
            }

            return redirect()
                ->back()
                ->with('success', 'This report is already verified.');
        }

        if ((string) $incident->otp !== (string) $request->otp) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'errors' => ['otp' => ['Invalid OTP. Please check the code sent to your email.']],
                ], 422);
            }

            return redirect()
                ->back()
                ->withErrors(['otp' => 'Invalid OTP. Please check the code sent to your email.'])
                ->withInput();
        }

        DB::table('incidents')
            ->where('id', $incident->id)
            ->update([
                'is_verified' => 1,
                'otp' => '',
                'updated_at' => now(),
            ]);

        InvestigatorNotification::notifyActiveInvestigators([
            'incident_id' => $incident->id,
            'type' => 'report',
            'priority' => 'high',
            'title' => 'New Verified Incident Report',
            'message' => 'Report ' . $incident->report_number . ' (' . ($incident->incident_type ?? 'Incident') . ') at ' . ($incident->location_name ?? 'Unknown location') . ' has been OTP verified and is ready for investigator action.',
            'action_url' => route('investigator.incident.view.case.page', ['incident' => $incident->id]),
        ]);

        AuditTrailLog::record([
            'incident_id' => $incident->id,
            'action_type' => 'public_report_verify',
            'action_performed' => 'Public incident report OTP verified and marked ready for investigator action.',
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully. Your report is now confirmed.',
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'OTP verified successfully. Your report is now confirmed.');
    }

    private function sendOtpEmail(string $reporterEmail, string $reportNumber, string $otp): void
    {
        Mail::send('emails.report_otp', [
            'reportNumber' => $reportNumber,
            'otp' => $otp,
        ], function ($message) use ($reporterEmail, $reportNumber) {
            $message->to($reporterEmail)
                ->subject('TrackForce Lipa OTP Verification - ' . $reportNumber);
        });
    }
}
