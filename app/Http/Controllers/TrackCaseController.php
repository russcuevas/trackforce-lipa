<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackCaseController extends Controller
{
    public function TrackCasePage(Request $request)
    {
        $referenceNumber = strtoupper(trim((string) $request->query('reference_number', '')));
        $incident = null;

        if ($referenceNumber !== '') {
            $incident = DB::table('incidents')
                ->leftJoin('investigators', 'investigators.id', '=', 'incidents.assigned_investigator_id')
                ->where('incidents.report_number', $referenceNumber)
                ->select(
                    'incidents.report_number',
                    'incidents.status',
                    'incidents.incident_type',
                    'incidents.created_at',
                    'incidents.location_name',
                    'incidents.time_completed',
                    'investigators.full_name as investigator_name',
                    'investigators.badge_number as investigator_badge'
                )
                ->first();
        }

        return view('track_reports', [
            'incident' => $incident,
            'referenceNumber' => $referenceNumber,
            'wasSearched' => $referenceNumber !== '',
        ]);
    }
}
