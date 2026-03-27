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

     public function IncidentCaseViewPage()
    {
        return view('investigator.incidents.case_sample');
    }

    public function IncidentPrintCaseRequest()
    {
        return view('investigator.incidents.print.print_case');
    }
}
