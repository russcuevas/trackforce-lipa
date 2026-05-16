<?php

namespace App\Http\Controllers\investigator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrintController extends Controller
{
    public function PrintReportsPage()
    {
        $incidents = DB::table('incidents')
            ->where('is_verified', 1)
            ->orderByDesc('created_at')
            ->get();

        return view('investigator.print.index', [
            'incidents' => $incidents,
        ]);
    }
}
