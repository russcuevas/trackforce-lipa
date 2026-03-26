<?php

namespace App\Http\Controllers\investigator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function DashboardPage()
    {
        return view('investigator.dashboard.index');
    }
}
