<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function LoginPage()
    {
        if (Auth::guard('investigator')->check()) {
            return redirect()->route('investigator.dashboard.page');
        }

        return view('auth.login');
    }

    public function LoginRequest(Request $request)
    {
        $credentials = $request->validate([
            'identifier' => ['required', 'string'],
            'password'   => ['required', 'string'],
        ]);

        $field = filter_var($credentials['identifier'], FILTER_VALIDATE_EMAIL) ? 'email' : 'badge_number';

        $attempt = Auth::guard('investigator')->attempt([
            $field    => $credentials['identifier'],
            'password' => $credentials['password'],
        ], $request->boolean('remember'));

        if (!$attempt) {
            return back()->withErrors(['identifier' => 'Invalid credentials. Please try again.'])->withInput($request->only('identifier'));
        }

        $request->session()->regenerate();

        return redirect()->intended(route('investigator.dashboard.page'))->with('success', 'You have logged in successfully.');
    }

    public function LogoutRequest(Request $request)
    {
        Auth::guard('investigator')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login.page')->with('success', 'You have been logged out successfully.');
    }
}
