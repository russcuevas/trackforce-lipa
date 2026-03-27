<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrackCaseController extends Controller
{
        public function TrackCasePage()
    {
        return view('track_reports');
    }
}
