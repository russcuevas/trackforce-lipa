<?php

namespace App\Http\Controllers\investigator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    public function DocumentationPage()
    {
        return view('investigator.documentations.index');
    }
}
