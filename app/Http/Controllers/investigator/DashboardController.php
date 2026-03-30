<?php

namespace App\Http\Controllers\investigator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function DashboardPage(Request $request)
    {
        $filters = $this->resolveDashboardFilters($request);
        $data = $this->buildDashboardData($filters);

        return view('investigator.dashboard.index', $data);
    }

    public function DashboardData(Request $request)
    {
        $filters = $this->resolveDashboardFilters($request);
        $data = $this->buildDashboardData($filters);

        return response()->json([
            'total_incidents' => $data['totalIncidents'],
            'accepted_count' => $data['acceptedCount'],
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
            'incident_type_breakdown' => $data['incidentTypeBreakdown'],
            'vehicle_type_breakdown' => $data['vehicleTypeBreakdown'],
            'selected_month' => $data['selectedMonth'],
            'selected_year' => $data['selectedYear'],
            'updated_at' => now()->toDateTimeString(),
        ]);
    }

    private function resolveDashboardFilters(Request $request): array
    {
        $month = $request->filled('month') ? (int) $request->input('month') : null;
        $year = $request->filled('year') ? (int) $request->input('year') : null;

        if ($month !== null && ($month < 1 || $month > 12)) {
            $month = null;
        }

        if ($year !== null && ($year < 2000 || $year > 2100)) {
            $year = null;
        }

        return [
            'month' => $month,
            'year' => $year,
        ];
    }

    private function applyIncidentDateFilter($query, ?int $month, ?int $year, string $column = 'created_at'): void
    {
        if ($month !== null) {
            $query->whereMonth($column, $month);
        }

        if ($year !== null) {
            $query->whereYear($column, $year);
        }
    }

    private function buildDashboardData(array $filters = []): array
    {
        $selectedMonth = $filters['month'] ?? null;
        $selectedYear = $filters['year'] ?? null;

        $totalIncidents = DB::table('incidents')
            ->where('is_verified', 1);

        $this->applyIncidentDateFilter($totalIncidents, $selectedMonth, $selectedYear);

        $totalIncidents = $totalIncidents
            ->count();

        $underInvestigationCount = DB::table('incidents')
            ->where('is_verified', 1)
            ->tap(fn($query) => $this->applyIncidentDateFilter($query, $selectedMonth, $selectedYear))
            ->whereIn(DB::raw('LOWER(COALESCE(status, ""))'), ['under investigation', 'investigating', 'in progress'])
            ->count();

        $acceptedCount = DB::table('incidents')
            ->where('is_verified', 1)
            ->tap(fn($query) => $this->applyIncidentDateFilter($query, $selectedMonth, $selectedYear))
            ->where(DB::raw('LOWER(COALESCE(status, ""))'), '=', 'accepted')
            ->count();

        $resolvedTodayCount = DB::table('incidents')
            ->where('is_verified', 1)
            ->tap(fn($query) => $this->applyIncidentDateFilter($query, $selectedMonth, $selectedYear))
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhereIn(DB::raw('LOWER(status)'), ['resolved', 'completed']);
            })
            ->count();

        $pendingReviewCount = DB::table('incidents')
            ->where('is_verified', 1)
            ->tap(fn($query) => $this->applyIncidentDateFilter($query, $selectedMonth, $selectedYear))
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhereIn(DB::raw('LOWER(status)'), ['pending', 'pending review']);
            })
            ->count();

        $recentIncidents = DB::table('incidents')
            ->where('is_verified', 1)
            ->tap(fn($query) => $this->applyIncidentDateFilter($query, $selectedMonth, $selectedYear))
            ->select('report_number', 'incident_type', 'location_name', 'status', 'created_at')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $mapIncidents = DB::table('incidents')
            ->where('is_verified', 1)
            ->tap(fn($query) => $this->applyIncidentDateFilter($query, $selectedMonth, $selectedYear))
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
            ->where('is_verified', 1)
            ->tap(fn($query) => $this->applyIncidentDateFilter($query, $selectedMonth, $selectedYear))
            ->whereNotNull('location_name')
            ->where('location_name', '!=', '')
            ->groupBy('location_name')
            ->orderByDesc('total_incidents')
            ->limit(10)
            ->get();

        $ageBuckets = DB::table('involved_parties')
            ->join('incidents', 'incidents.id', '=', 'involved_parties.incident_id')
            ->where('incidents.is_verified', 1)
            ->tap(fn($query) => $this->applyIncidentDateFilter($query, $selectedMonth, $selectedYear, 'incidents.created_at'))
            ->selectRaw(
                'SUM(CASE WHEN age <= 17 THEN 1 ELSE 0 END) as age_17_below,
                 SUM(CASE WHEN age BETWEEN 18 AND 30 THEN 1 ELSE 0 END) as age_18_30,
                 SUM(CASE WHEN age BETWEEN 31 AND 40 THEN 1 ELSE 0 END) as age_31_40,
                 SUM(CASE WHEN age BETWEEN 41 AND 59 THEN 1 ELSE 0 END) as age_41_59,
                 SUM(CASE WHEN age >= 60 THEN 1 ELSE 0 END) as age_60_plus'
            )
            ->first();

        $sexBuckets = DB::table('involved_parties')
            ->join('incidents', 'incidents.id', '=', 'involved_parties.incident_id')
            ->where('incidents.is_verified', 1)
            ->tap(fn($query) => $this->applyIncidentDateFilter($query, $selectedMonth, $selectedYear, 'incidents.created_at'))
            ->selectRaw(
                'SUM(CASE WHEN LOWER(sex) = "male" THEN 1 ELSE 0 END) as male,
                 SUM(CASE WHEN LOWER(sex) = "female" THEN 1 ELSE 0 END) as female'
            )
            ->first();

        $incidentTypeCounts = DB::table('incidents')
            ->where('is_verified', 1)
            ->tap(fn($query) => $this->applyIncidentDateFilter($query, $selectedMonth, $selectedYear))
            ->whereNotNull('incident_type')
            ->where('incident_type', '!=', '')
            ->select('incident_type', DB::raw('COUNT(*) as total_count'))
            ->groupBy('incident_type')
            ->orderByDesc('total_count')
            ->limit(10)
            ->get();

        $incidentTypeTotal = (int) $incidentTypeCounts->sum('total_count');
        $incidentTypeTop = $incidentTypeCounts->first();

        $vehicleTypeCounts = DB::table('vehicles')
            ->join('incidents', 'incidents.id', '=', 'vehicles.incident_id')
            ->where('incidents.is_verified', 1)
            ->tap(fn($query) => $this->applyIncidentDateFilter($query, $selectedMonth, $selectedYear, 'incidents.created_at'))
            ->whereNotNull('vehicles.vehicle_type')
            ->where('vehicles.vehicle_type', '!=', '')
            ->select('vehicles.vehicle_type', DB::raw('COUNT(*) as total_count'))
            ->groupBy('vehicles.vehicle_type')
            ->orderByDesc('total_count')
            ->limit(10)
            ->get();

        $vehicleTypeTotal = (int) $vehicleTypeCounts->sum('total_count');
        $vehicleTypeTop = $vehicleTypeCounts->first();

        $availableYears = DB::table('incidents')
            ->where('is_verified', 1)
            ->whereNotNull('created_at')
            ->selectRaw('YEAR(created_at) as year_value')
            ->groupBy('year_value')
            ->orderByDesc('year_value')
            ->pluck('year_value')
            ->map(fn($yearValue) => (int) $yearValue)
            ->filter(fn($yearValue) => $yearValue > 0)
            ->values();

        if ($availableYears->isEmpty()) {
            $availableYears = collect([(int) now()->year]);
        }

        if ($selectedYear !== null && !$availableYears->contains($selectedYear)) {
            $availableYears->prepend($selectedYear);
            $availableYears = $availableYears->unique()->sortDesc()->values();
        }

        return [
            'totalIncidents' => $totalIncidents,
            'acceptedCount' => $acceptedCount,
            'underInvestigationCount' => $underInvestigationCount,
            'resolvedTodayCount' => $resolvedTodayCount,
            'pendingReviewCount' => $pendingReviewCount,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'availableYears' => $availableYears,
            'recentIncidents' => $recentIncidents,
            'mapIncidents' => $mapIncidents,
            'addressCounts' => $addressCounts,
            'ageChartData' => [
                (int) ($ageBuckets->age_17_below ?? 0),
                (int) ($ageBuckets->age_18_30 ?? 0),
                (int) ($ageBuckets->age_31_40 ?? 0),
                (int) ($ageBuckets->age_41_59 ?? 0),
                (int) ($ageBuckets->age_60_plus ?? 0),
            ],
            'sexChartData' => [
                (int) ($sexBuckets->male ?? 0),
                (int) ($sexBuckets->female ?? 0),
            ],
            'incidentTypeBreakdown' => [
                'labels' => $incidentTypeCounts->pluck('incident_type')->values(),
                'counts' => $incidentTypeCounts->pluck('total_count')->map(fn($count) => (int) $count)->values(),
                'total' => $incidentTypeTotal,
                'top_label' => $incidentTypeTop->incident_type ?? null,
                'top_count' => (int) ($incidentTypeTop->total_count ?? 0),
                'top_percent' => $incidentTypeTotal > 0
                    ? round((((int) ($incidentTypeTop->total_count ?? 0)) / $incidentTypeTotal) * 100, 2)
                    : 0,
            ],
            'vehicleTypeBreakdown' => [
                'labels' => $vehicleTypeCounts->pluck('vehicle_type')->values(),
                'counts' => $vehicleTypeCounts->pluck('total_count')->map(fn($count) => (int) $count)->values(),
                'total' => $vehicleTypeTotal,
                'top_label' => $vehicleTypeTop->vehicle_type ?? null,
                'top_count' => (int) ($vehicleTypeTop->total_count ?? 0),
                'top_percent' => $vehicleTypeTotal > 0
                    ? round((((int) ($vehicleTypeTop->total_count ?? 0)) / $vehicleTypeTotal) * 100, 2)
                    : 0,
            ],
        ];
    }
}
