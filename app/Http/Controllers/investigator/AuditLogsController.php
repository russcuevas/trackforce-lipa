<?php

namespace App\Http\Controllers\investigator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuditLogsController extends Controller
{
    public function LogsPage()
    {
        return view('investigator.logs.index');
    }
}
