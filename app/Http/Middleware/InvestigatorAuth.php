<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class InvestigatorAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('investigator')->check()) {
            return redirect()->route('auth.login.page')->with('error', 'Please log in to access this page.');
        }

        return $next($request);
    }
}
