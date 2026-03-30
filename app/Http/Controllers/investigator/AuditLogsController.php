<?php

namespace App\Http\Controllers\investigator;

use App\Http\Controllers\Controller;
use App\Models\AuditTrailLog;
use Illuminate\Http\Request;

class AuditLogsController extends Controller
{
    public function LogsPage()
    {
        $logs = AuditTrailLog::query()
            ->with(['incident:id,report_number', 'investigator:id,full_name'])
            ->latest('created_at')
            ->get();

        return view('investigator.logs.index', [
            'logs' => $logs,
        ]);
    }
}
