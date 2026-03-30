<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\AuditTrailLog;
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

        $field = filter_var($credentials['identifier'], FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'badge_number';

        // ✅ Add status condition here
        $attempt = Auth::guard('investigator')->attempt([
            $field     => $credentials['identifier'],
            'password' => $credentials['password'],
            'status'   => 'Active',
        ], $request->boolean('remember'));

        if (!$attempt) {

            // Optional: Check if account exists but not active
            $user = \App\Models\Investigator::where($field, $credentials['identifier'])->first();

            if ($user && $user->status !== 'Active') {
                return back()->withErrors([
                    'identifier' => 'Cannot login. Your account is not active. Please contact the administrator.'
                ])->withInput($request->only('identifier'));
            }

            return back()->withErrors([
                'identifier' => 'Invalid credentials. Please try again.'
            ])->withInput($request->only('identifier'));
        }

        $request->session()->regenerate();

        $investigator = Auth::guard('investigator')->user();
        AuditTrailLog::record([
            'investigator_id' => $investigator?->id,
            'action_type' => 'auth_login',
            'action_performed' => 'Logged in to the investigator portal.',
        ]);

        return redirect()->intended(route('investigator.dashboard.page'))
            ->with('success', 'You have logged in successfully.');
    }

    public function LogoutRequest(Request $request)
    {
        $investigator = Auth::guard('investigator')->user();

        AuditTrailLog::record([
            'investigator_id' => $investigator?->id,
            'action_type' => 'auth_logout',
            'action_performed' => 'Logged out from the investigator portal.',
        ]);

        Auth::guard('investigator')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login.page')->with('success', 'You have been logged out successfully.');
    }
}
