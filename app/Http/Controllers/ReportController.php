<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function ReportPage()
    {
        return view('report');
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

        // ✅ Return success JSON for AJAX
        return response()->json([
            'success' => true,
            'report_number' => $reportNumber,
        ]);
    }
}
