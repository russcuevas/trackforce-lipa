<?php

namespace App\Http\Controllers\investigator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function DashboardPage()
    {
        $data = $this->buildDashboardData();

        return view('investigator.dashboard.index', $data);
    }

    public function DashboardData()
    {
        $data = $this->buildDashboardData();

        return response()->json([
            'total_incidents' => $data['totalIncidents'],
            'under_investigation_count' => $data['underInvestigationCount'],
            'resolved_today_count' => $data['resolvedTodayCount'],
            'pending_review_count' => $data['pendingReviewCount'],
            'recent_incidents' => $data['recentIncidents']->map(function ($incident) {
                return [
                    'report_number' => $incident->report_number,
                    'incident_type' => $incident->incident_type,
                    'location_name' => $incident->location_name,
                    'status' => $incident->status ?? 'Pending',
                    'created_at' => (string) $incident->created_at,
                    'created_at_human' => \Illuminate\Support\Carbon::parse($incident->created_at)->diffForHumans(),
                ];
            })->values(),
            'map_incidents' => $data['mapIncidents'],
            'address_counts' => $data['addressCounts']->values(),
            'age_chart_data' => $data['ageChartData'],
            'sex_chart_data' => $data['sexChartData'],
            'updated_at' => now()->toDateTimeString(),
        ]);
    }

    private function buildDashboardData(): array
    {
        $totalIncidents = DB::table('incidents')->count();

        $underInvestigationCount = DB::table('incidents')
            ->whereIn(DB::raw('LOWER(COALESCE(status, ""))'), ['under investigation', 'investigating', 'in progress'])
            ->count();

        $resolvedTodayCount = DB::table('incidents')
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhereIn(DB::raw('LOWER(status)'), ['resolved', 'completed']);
            })
            ->count();

        $pendingReviewCount = DB::table('incidents')
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhereIn(DB::raw('LOWER(status)'), ['pending', 'pending review']);
            })
            ->count();

        $recentIncidents = DB::table('incidents')
            ->select('report_number', 'incident_type', 'location_name', 'status', 'created_at')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $mapIncidents = DB::table('incidents')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select('report_number', 'incident_type', 'location_name', 'status', 'latitude', 'longitude')
            ->orderByDesc('created_at')
            ->limit(300)
            ->get()
            ->map(function ($incident) {
                return [
                    'id' => $incident->report_number,
                    'lat' => (float) $incident->latitude,
                    'lng' => (float) $incident->longitude,
                    'title' => trim(($incident->incident_type ?? 'Incident') . ' - ' . ($incident->location_name ?? 'Unknown location')),
                    'status' => $incident->status ?? 'Pending',
                ];
            })
            ->values();

        $addressCounts = DB::table('incidents')
            ->select('location_name', DB::raw('COUNT(*) as total_incidents'))
            ->whereNotNull('location_name')
            ->where('location_name', '!=', '')
            ->groupBy('location_name')
            ->orderByDesc('total_incidents')
            ->limit(10)
            ->get();

        $ageBuckets = DB::table('involved_parties')
            ->selectRaw(
                'SUM(CASE WHEN age BETWEEN 18 AND 20 THEN 1 ELSE 0 END) as age_18_20,
                 SUM(CASE WHEN age BETWEEN 21 AND 30 THEN 1 ELSE 0 END) as age_21_30,
                 SUM(CASE WHEN age BETWEEN 31 AND 40 THEN 1 ELSE 0 END) as age_31_40,
                 SUM(CASE WHEN age >= 41 THEN 1 ELSE 0 END) as age_41_plus'
            )
            ->first();

        $sexBuckets = DB::table('involved_parties')
            ->selectRaw(
                'SUM(CASE WHEN LOWER(sex) = "male" THEN 1 ELSE 0 END) as male,
                 SUM(CASE WHEN LOWER(sex) = "female" THEN 1 ELSE 0 END) as female,
                 SUM(CASE WHEN sex IS NOT NULL AND sex != "" AND LOWER(sex) NOT IN ("male", "female") THEN 1 ELSE 0 END) as other'
            )
            ->first();

        return [
            'totalIncidents' => $totalIncidents,
            'underInvestigationCount' => $underInvestigationCount,
            'resolvedTodayCount' => $resolvedTodayCount,
            'pendingReviewCount' => $pendingReviewCount,
            'recentIncidents' => $recentIncidents,
            'mapIncidents' => $mapIncidents,
            'addressCounts' => $addressCounts,
            'ageChartData' => [
                (int) ($ageBuckets->age_18_20 ?? 0),
                (int) ($ageBuckets->age_21_30 ?? 0),
                (int) ($ageBuckets->age_31_40 ?? 0),
                (int) ($ageBuckets->age_41_plus ?? 0),
            ],
            'sexChartData' => [
                (int) ($sexBuckets->male ?? 0),
                (int) ($sexBuckets->female ?? 0),
                (int) ($sexBuckets->other ?? 0),
            ],
        ];
    }
}
