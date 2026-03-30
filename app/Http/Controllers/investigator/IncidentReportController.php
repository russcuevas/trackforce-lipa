<?php

namespace App\Http\Controllers\investigator;

use App\Http\Controllers\Controller;
use App\Models\IncidentEvidence;
use App\Models\Incidents;
use App\Models\InvolvedParties;
use App\Models\Vehicles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class IncidentReportController extends Controller
{
    public function IncidentReportPage()
    {
        $incidents = Incidents::query()
            ->latest('created_at')
            ->get();

        $stats = [
            'all' => $incidents->count(),
            'pending' => $incidents->filter(function ($incident) {
                $status = strtolower(trim((string) $incident->status));

                return in_array($status, ['pending', 'pending review'], true);
            })->count(),
            'accepted' => $incidents->filter(function ($incident) {
                return strtolower(trim((string) $incident->status)) === 'accepted';
            })->count(),
            'resolved' => $incidents->filter(function ($incident) {
                return strtolower(trim((string) $incident->status)) === 'resolved';
            })->count(),
            'under_investigation' => $incidents->filter(function ($incident) {
                return strtolower(trim((string) $incident->status)) === 'under investigation';
            })->count(),
        ];

        return view('investigator.incidents.index', [
            'incidents' => $incidents,
            'stats' => $stats,
        ]);
    }

    public function IncidentCaseViewPage(Incidents $incident)
    {
        $incident->loadMissing('assignedInvestigator');

        $involvedParties = InvolvedParties::query()
            ->where('incident_id', $incident->id)
            ->get();

        $vehicles = Vehicles::query()
            ->where('incident_id', $incident->id)
            ->get();

        $evidences = IncidentEvidence::query()
            ->where('incident_id', $incident->id)
            ->latest('uploaded_at')
            ->get();

        return view('investigator.incidents.case_sample', [
            'incident' => $incident,
            'involvedParties' => $involvedParties,
            'vehicles' => $vehicles,
            'evidences' => $evidences,
        ]);
    }

    public function IncidentPrintCaseRequest()
    {
        return view('investigator.incidents.print.print_case');
    }

    public function UpdateIncidentStatusRequest(Request $request, Incidents $incident)
    {
        $validated = $request->validate([
            'status' => 'required|in:Accepted,Declined,Resolved',
        ]);

        $currentStatus = strtolower(trim((string) $incident->status));
        if (in_array($validated['status'], ['Accepted', 'Declined'], true)) {
            if (!in_array($currentStatus, ['pending', 'pending review'], true)) {
                return back()->withErrors([
                    'status' => 'Only pending incidents can be accepted or declined.',
                ]);
            }
        }

        if ($validated['status'] === 'Resolved') {
            if (!in_array($currentStatus, ['under investigation', 'investigating', 'in progress'], true)) {
                return back()->withErrors([
                    'status' => 'Only under-investigation incidents can be completed.',
                ]);
            }

            if (
                !empty($incident->assigned_investigator_id)
                && (int) $incident->assigned_investigator_id !== (int) Auth::guard('investigator')->id()
            ) {
                return back()->withErrors([
                    'status' => 'Only the assigned investigator can complete this case.',
                ]);
            }
        }

        $incident->status = $validated['status'];

        if ($validated['status'] === 'Accepted') {
            $incident->assigned_investigator_id = Auth::guard('investigator')->id();
            if (empty($incident->time_accepted)) {
                $incident->time_accepted = now();
            }
            $incident->time_completed = null;
        }

        if ($validated['status'] === 'Declined') {
            $incident->time_completed = now();
        }

        if ($validated['status'] === 'Resolved') {
            $incident->time_completed = now();
        }

        $incident->save();

        return redirect()
            ->route('investigator.incident.view.case.page', ['incident' => $incident->id])
            ->with('success', 'Incident status updated to ' . $validated['status'] . '.');
    }

    public function UpdateIncidentDetailsRequest(Request $request, Incidents $incident)
    {
        $investigator = Auth::guard('investigator')->user();

        if (!$investigator) {
            return back()->withErrors(['general' => 'You must be logged in as investigator.'])->withInput();
        }

        $currentStatus = strtolower(trim((string) $incident->status));
        if ($currentStatus !== 'accepted') {
            return back()->withErrors([
                'status' => 'Only accepted incidents can be edited from this page.',
            ])->withInput();
        }

        if (
            !empty($incident->assigned_investigator_id)
            && (int) $incident->assigned_investigator_id !== (int) $investigator->id
        ) {
            return back()->withErrors([
                'status' => 'Only the assigned investigator can update this case.',
            ])->withInput();
        }

        $validator = Validator::make($request->all(), [
            'incident_type' => 'required|string|max:100',
            'incident_type_other' => 'required_if:incident_type,Other|nullable|string|max:100',
            'road_condition' => 'nullable|string|max:100',
            'weather_condition' => 'nullable|string|max:100',
            'party.*.name' => 'nullable|string|max:255',
            'party.*.age' => 'nullable|integer|min:0|max:150',
            'party.*.sex' => 'nullable|in:Male,Female,male,female',
            'party.*.role' => 'nullable|string|max:50',
            'party.*.license_number' => 'nullable|string',
            'party.*.severity' => 'nullable|string|max:50',
            'party.*.statement' => 'nullable|string',
        ], [
            'incident_type_other.required_if' => 'Please specify the incident type when selecting Other.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $incidentType = ($validated['incident_type'] ?? '') === 'Other'
            ? ($validated['incident_type_other'] ?? 'Other')
            : $validated['incident_type'];

        DB::transaction(function () use ($request, $validated, $incident, $incidentType, $investigator) {
            $incident->update([
                'incident_type' => $incidentType,
                'road_condition' => $validated['road_condition'] ?? null,
                'weather_condition' => $validated['weather_condition'] ?? null,
                'status' => 'Under Investigation',
                'assigned_investigator_id' => $investigator->id,
                'time_accepted' => $incident->time_accepted ?: now(),
                'time_under_investigation' => $incident->time_under_investigation ?: now(),
                'time_documented' => $incident->time_documented ?: now(),
                'time_completed' => null,
            ]);

            DB::table('involved_parties')
                ->where('incident_id', $incident->id)
                ->delete();

            foreach ($request->input('party', []) as $party) {
                $name = trim((string) ($party['name'] ?? ''));
                $age = $party['age'] ?? null;
                $sexRaw = trim((string) ($party['sex'] ?? ''));
                $sex = $sexRaw !== '' ? ucfirst(strtolower($sexRaw)) : null;
                $role = trim((string) ($party['role'] ?? ''));
                $licenseNumber = trim((string) ($party['license_number'] ?? ''));
                $severity = trim((string) ($party['severity'] ?? ''));
                $statement = trim((string) ($party['statement'] ?? ''));

                $hasAnyField = collect([
                    $name,
                    $age,
                    $sex,
                    $role,
                    $licenseNumber,
                    $severity,
                    $statement,
                ])->filter(fn($value) => $value !== null && $value !== '')->isNotEmpty();

                if (!$hasAnyField) {
                    continue;
                }

                DB::table('involved_parties')->insert([
                    'incident_id' => $incident->id,
                    'full_name' => $name !== '' ? $name : null,
                    'age' => $age,
                    'sex' => $sex,
                    'role' => $role !== '' ? $role : null,
                    'license_number' => $licenseNumber !== '' ? $licenseNumber : null,
                    'injury_severity' => $severity !== '' ? $severity : null,
                    'statement' => $statement !== '' ? $statement : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        return redirect()
            ->route('investigator.incident.view.case.page', ['incident' => $incident->id])
            ->with('success', 'Case details updated. Status set to Under Investigation.');
    }

    public function CreateIncidentRequest(Request $request)
    {
        $investigator = Auth::guard('investigator')->user();

        if (!$investigator) {
            return back()->withErrors(['general' => 'You must be logged in as investigator.']);
        }

        $validator = Validator::make($request->all(), [
            'incident_type' => 'required|string|max:100',
            'incident_type_other' => 'required_if:incident_type,Other|nullable|string|max:100',
            'location_name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'road_condition' => 'nullable|string|max:100',
            'weather_condition' => 'nullable|string|max:100',
            'reporter_name' => 'nullable|string|max:255',
            'reporter_contact' => 'nullable|string|max:50',
            'reporter_email' => 'nullable|email|max:100',
            'reporter_address' => 'nullable|string',
            'party.*.name' => 'nullable|string|max:255',
            'party.*.age' => 'nullable|integer|min:0|max:150',
            'party.*.sex' => 'nullable|in:Male,Female,male,female',
            'party.*.role' => 'nullable|string|max:50',
            'party.*.license_number' => 'nullable|string',
            'party.*.severity' => 'nullable|string|max:50',
            'party.*.statement' => 'nullable|string',
            'vehicle_type.*' => 'nullable|string|max:50',
            'vehicle_type_other.*' => 'nullable|string|max:50',
            'plate_number.*' => 'nullable|string|max:20',
            'vehicle_color.*' => 'nullable|string|max:30',
            'evidence.*' => 'file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:10240',
        ], [
            'incident_type_other.required_if' => 'Please specify the incident type when selecting Other.',
            'evidence.*.mimes' => 'The evidence field must be a file of type: jpg, jpeg, png, gif, mp4, mov, avi.',
            'evidence.*.max' => 'Each evidence file must not exceed 10MB.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $year = date('Y');
        $count = DB::table('incidents')->whereYear('created_at', $year)->count() + 1;
        $reportNumber = 'TFL-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $incidentType = ($validated['incident_type'] ?? '') === 'Other'
            ? ($validated['incident_type_other'] ?? 'Other')
            : $validated['incident_type'];

        $incidentId = DB::transaction(function () use ($request, $validated, $investigator, $reportNumber, $incidentType) {
            $incidentId = DB::table('incidents')->insertGetId([
                'report_number' => $reportNumber,
                'incident_type' => $incidentType,
                'location_name' => $validated['location_name'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'road_condition' => $validated['road_condition'] ?? null,
                'weather_condition' => $validated['weather_condition'] ?? null,
                'reporter_name' => $validated['reporter_name'] ?? null,
                'reporter_contact' => $validated['reporter_contact'] ?? null,
                'reporter_email' => $validated['reporter_email'] ?? null,
                'reporter_address' => $validated['reporter_address'] ?? null,
                'status' => 'Under Investigation',
                'otp' => '',
                'is_verified' => 1,
                'assigned_investigator_id' => $investigator->id,
                'time_accepted' => now(),
                'time_under_investigation' => now(),
                'time_documented' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $parties = $request->input('party', []);

            if (!empty($parties)) {
                foreach ($parties as $party) {
                    $name = trim((string) ($party['name'] ?? ''));
                    $age = $party['age'] ?? null;
                    $sexRaw = trim((string) ($party['sex'] ?? ''));
                    $sex = $sexRaw !== '' ? ucfirst(strtolower($sexRaw)) : null;
                    $role = trim((string) ($party['role'] ?? ''));
                    $licenseNumber = trim((string) ($party['license_number'] ?? ''));
                    $severity = trim((string) ($party['severity'] ?? ''));
                    $statement = trim((string) ($party['statement'] ?? ''));

                    $hasAnyField = collect([
                        $name,
                        $age,
                        $sex,
                        $role,
                        $licenseNumber,
                        $severity,
                        $statement,
                    ])->filter(fn($value) => $value !== null && $value !== '')->isNotEmpty();

                    if (!$hasAnyField) {
                        continue;
                    }

                    DB::table('involved_parties')->insert([
                        'incident_id' => $incidentId,
                        'full_name' => $name !== '' ? $name : null,
                        'age' => $age,
                        'sex' => $sex,
                        'role' => $role !== '' ? $role : null,
                        'license_number' => $licenseNumber !== '' ? $licenseNumber : null,
                        'injury_severity' => $severity !== '' ? $severity : null,
                        'statement' => $statement !== '' ? $statement : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
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
            }

            $vehicleTypes = $request->input('vehicle_type', []);
            $vehicleTypeOthers = $request->input('vehicle_type_other', []);
            $plateNumbers = $request->input('plate_number', []);
            $vehicleColors = $request->input('vehicle_color', []);

            foreach ($vehicleTypes as $index => $vehicleTypeRaw) {
                $vehicleTypeRaw = (string) $vehicleTypeRaw;
                $vehicleType = $vehicleTypeRaw === 'Other'
                    ? ($vehicleTypeOthers[$index] ?? null)
                    : $vehicleTypeRaw;

                $plate = $plateNumbers[$index] ?? null;
                $color = $vehicleColors[$index] ?? null;

                if (!$vehicleType && !$plate && !$color) {
                    continue;
                }

                DB::table('vehicles')->insert([
                    'incident_id' => $incidentId,
                    'vehicle_type' => $vehicleType,
                    'plate_number' => $plate,
                    'color' => $color,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if ($request->hasFile('evidence')) {
                foreach ($request->file('evidence') as $file) {
                    $filename = $file->hashName();
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

            return $incidentId;
        });

        return redirect()
            ->route('investigator.incident.report.page')
            ->with('success', 'Incident saved as Under Investigation. Report #: ' . $reportNumber);
    }
}
