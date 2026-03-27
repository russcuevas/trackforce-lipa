<?php

namespace App\Http\Controllers\investigator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function AccountPage()
    {
        return view('investigator.accounts.index');
    }
}
