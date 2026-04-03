<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\AuditTrailLog;
use App\Models\Investigator;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function LoginPage()
    {
        if (Auth::guard('investigator')->check()) {
            return redirect()->route('investigator.dashboard.page');
        }

        return view('auth.login');
    }

    public function ForgotPasswordPage()
    {
        if (Auth::guard('investigator')->check()) {
            return redirect()->route('investigator.dashboard.page');
        }

        return view('auth.forgot-password');
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

    public function SendResetLinkRequest(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::broker('investigators')->sendResetLink([
            'email' => $validated['email'],
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', __($status));
        }

        return back()->withErrors([
            'email' => __($status),
        ])->withInput($request->only('email'));
    }

    public function ResetPasswordPage(Request $request, string $token)
    {
        if (Auth::guard('investigator')->check()) {
            return redirect()->route('investigator.dashboard.page');
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => (string) $request->query('email', ''),
        ]);
    }

    public function ResetPasswordRequest(Request $request)
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $status = Password::broker('investigators')->reset(
            $validated,
            function (Investigator $investigator, string $password) {
                $investigator->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                event(new PasswordReset($investigator));

                AuditTrailLog::record([
                    'investigator_id' => $investigator->id,
                    'action_type' => 'password_reset',
                    'action_performed' => 'Reset account password via forgot password flow.',
                ]);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('auth.login.page')->with('success', __($status));
        }

        return back()->withErrors([
            'email' => __($status),
        ])->withInput($request->only('email'));
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
