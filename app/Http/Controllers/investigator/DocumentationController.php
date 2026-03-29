<?php

namespace App\Http\Controllers\investigator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentationController extends Controller
{
    public function DocumentationPage()
    {
        $monthlyIncidentReports = DB::table('incidents')
            ->where('is_verified', 1)
            ->selectRaw(
                'YEAR(created_at) as year_value,
                 MONTH(created_at) as month_value,
                 COUNT(*) as reports_count'
            )
            ->groupBy('year_value', 'month_value')
            ->orderByDesc('year_value')
            ->orderByDesc('month_value')
            ->get();

        return view('investigator.documentations.index', [
            'monthlyIncidentReports' => $monthlyIncidentReports,
        ]);
    }

    public function DocumentationReportsPage(int $year, int $month)
    {
        if ($year < 2000 || $year > 2100 || $month < 1 || $month > 12) {
            abort(404);
        }

        $incidents = DB::table('incidents')
            ->where('is_verified', 1)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->select('id', 'report_number', 'incident_type', 'location_name', 'status', 'time_completed', 'created_at', 'assigned_investigator_id')
            ->orderByDesc('created_at')
            ->get();

        return view('investigator.documentations.reports', [
            'selectedYear' => $year,
            'selectedMonth' => $month,
            'incidents' => $incidents,
        ]);
    }

    public function DocumentationPrintReportPage(int $incident)
    {
        $incidentData = DB::table('incidents')
            ->leftJoin('investigators', 'investigators.id', '=', 'incidents.assigned_investigator_id')
            ->where('incidents.id', $incident)
            ->where('incidents.is_verified', 1)
            ->select(
                'incidents.*',
                'investigators.full_name as investigator_name',
                'investigators.badge_number as investigator_badge_number'
            )
            ->first();

        if (!$incidentData) {
            abort(404);
        }

        $involvedParties = DB::table('involved_parties')
            ->where('incident_id', $incidentData->id)
            ->orderBy('id')
            ->get();

        $vehicles = DB::table('vehicles')
            ->where('incident_id', $incidentData->id)
            ->orderBy('id')
            ->get();

        $evidences = DB::table('incident_evidence')
            ->where('incident_id', $incidentData->id)
            ->orderBy('id')
            ->get();

        return view('investigator.documentations.print.print_reports', [
            'incident' => $incidentData,
            'involvedParties' => $involvedParties,
            'vehicles' => $vehicles,
            'evidences' => $evidences,
        ]);
    }
}
