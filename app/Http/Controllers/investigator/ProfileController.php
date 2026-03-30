<?php

namespace App\Http\Controllers\investigator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function ProfilePage()
    {
        $investigator = Auth::guard('investigator')->user();

        return view('investigator.profile.index', compact('investigator'));
    }

    public function UpdateEmailRequest(Request $request)
    {
        $investigator = Auth::guard('investigator')->user();

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:investigators,email,' . $investigator->id],
        ]);

        $investigator->update($validated);

        return back()->with('success', 'Profile details updated successfully.');
    }

    public function UpdatePasswordRequest(Request $request)
    {
        $investigator = Auth::guard('investigator')->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($validated['current_password'], $investigator->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $investigator->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}
