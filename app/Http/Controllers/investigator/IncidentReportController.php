<?php

namespace App\Http\Controllers\investigator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IncidentReportController extends Controller
{
    public function IncidentReportPage()
    {
        return view('investigator.incidents.index');
    }
}
