<?php

namespace App\Http\Controllers\investigator;

use App\Http\Controllers\Controller;
use App\Models\AuditTrailLog;
use App\Models\Investigator;
use App\Models\InvestigatorNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function ProfilePage()
    {
        $investigator = Auth::guard('investigator')->user();

        if (!$investigator instanceof Investigator) {
            abort(403);
        }

        return view('investigator.profile.index', compact('investigator'));
    }

    public function UpdateEmailRequest(Request $request)
    {
        $investigator = Auth::guard('investigator')->user();

        if (!$investigator instanceof Investigator) {
            abort(403);
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:investigators,email,' . $investigator->id],
        ]);

        $investigator->update($validated);

        InvestigatorNotification::notifyInvestigator($investigator->id, [
            'created_by_investigator_id' => $investigator->id,
            'type' => 'system',
            'priority' => 'low',
            'title' => 'Profile Updated Successfully',
            'message' => 'Your profile name/email details were changed. If this was not you, contact your administrator immediately.',
            'action_url' => route('investigator.profile.page'),
        ]);

        AuditTrailLog::record([
            'investigator_id' => $investigator->id,
            'action_type' => 'profile_update',
            'action_performed' => 'Updated personal profile details.',
        ]);

        return back()->with('success', 'Profile details updated successfully.');
    }

    public function UpdatePasswordRequest(Request $request)
    {
        $investigator = Auth::guard('investigator')->user();

        if (!$investigator instanceof Investigator) {
            abort(403);
        }

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

        InvestigatorNotification::notifyInvestigator($investigator->id, [
            'created_by_investigator_id' => $investigator->id,
            'type' => 'system',
            'priority' => 'high',
            'title' => 'Password Changed',
            'message' => 'Your account password was updated. Keep this password private and do not reuse old credentials.',
            'action_url' => route('investigator.profile.page'),
        ]);

        AuditTrailLog::record([
            'investigator_id' => $investigator->id,
            'action_type' => 'password_change',
            'action_performed' => 'Changed account password.',
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}
