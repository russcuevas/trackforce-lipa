<?php

namespace App\Http\Controllers\investigator;

use App\Http\Controllers\Controller;
use App\Models\AuditTrailLog;
use App\Models\Investigator;
use App\Models\InvestigatorNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function AccountPage()
    {
        $investigators = Investigator::all();
        return view('investigator.accounts.index', compact('investigators'));
    }

    public function CreateAccountRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'badge_number' => 'required|string|unique:investigators,badge_number',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:investigators,email',
            'password' => 'required|min:8|confirmed',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('investigators', 'public');
            $validated['profile_image'] = $path;
        }

        $investigator = Investigator::create($validated);
        $investigator->refresh();

        $actor = Auth::guard('investigator')->user();

        InvestigatorNotification::notifyActiveInvestigators([
            'created_by_investigator_id' => $actor?->id,
            'type' => 'system',
            'priority' => 'medium',
            'title' => 'New Investigator Account Created',
            'message' => $investigator->full_name . ' (Badge #' . $investigator->badge_number . ') was added as an investigator account.',
            'action_url' => route('investigator.account.page'),
        ], [$investigator->id]);

        InvestigatorNotification::notifyInvestigator($investigator->id, [
            'created_by_investigator_id' => $actor?->id,
            'type' => 'system',
            'priority' => 'medium',
            'title' => 'Welcome to TrackForce Lipa',
            'message' => 'Your investigator account is now active. Review your profile details and keep your credentials secure.',
            'action_url' => route('investigator.profile.page'),
        ]);

        AuditTrailLog::record([
            'investigator_id' => $actor?->id,
            'action_type' => 'account_create',
            'action_performed' => 'Created investigator account for ' . $investigator->full_name . ' (Badge #' . $investigator->badge_number . ').',
        ]);

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'message' => 'Investigator account created successfully!',
                'investigator_id' => $investigator->id,
                'row_html' => view('investigator.accounts.partials.investigator_row', [
                    'investigator' => $investigator,
                ])->render(),
            ], 201);
        }

        return redirect()->route('investigator.account.page')->with('success', 'Investigator account created successfully!');
    }

    public function UpdateAccountRequest(Request $request, Investigator $investigator)
    {
        $validator = Validator::make($request->all(), [
            'badge_number' => 'required|string|unique:investigators,badge_number,' . $investigator->id,
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:investigators,email,' . $investigator->id,
            'status' => 'required|in:active,inactive,suspended',
            'password' => 'nullable|min:8|confirmed',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('profile_image')) {
            if ($investigator->profile_image) {
                Storage::disk('public')->delete($investigator->profile_image);
            }

            $path = $request->file('profile_image')->store('investigators', 'public');
            $validated['profile_image'] = $path;
        }

        $investigator->update($validated);

        $actor = Auth::guard('investigator')->user();

        InvestigatorNotification::notifyInvestigator($investigator->id, [
            'created_by_investigator_id' => $actor?->id,
            'type' => 'system',
            'priority' => 'medium',
            'title' => 'Your Account Details Were Updated',
            'message' => 'Your investigator account information was updated. Current status: ' . strtoupper((string) $investigator->status) . '.',
            'action_url' => route('investigator.profile.page'),
        ]);

        InvestigatorNotification::notifyActiveInvestigators([
            'created_by_investigator_id' => $actor?->id,
            'type' => 'system',
            'priority' => 'low',
            'title' => 'Investigator Account Updated',
            'message' => $investigator->full_name . ' (Badge #' . $investigator->badge_number . ') account details were updated.',
            'action_url' => route('investigator.account.page'),
        ], [$investigator->id]);

        AuditTrailLog::record([
            'investigator_id' => $actor?->id,
            'action_type' => 'account_update',
            'action_performed' => 'Updated investigator account for ' . $investigator->full_name . ' (Badge #' . $investigator->badge_number . ').',
        ]);

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'message' => 'Investigator account updated successfully!',
                // Add this line to return the new image path
                'profile_image_url' => $investigator->profile_image ? asset('storage/' . $investigator->profile_image) : null
            ]);
        }

        return redirect()->route('investigator.account.page')->with('success', 'Investigator account updated successfully!');
    }

    public function DeleteAccountRequest(Request $request, Investigator $investigator)
    {
        $actor = Auth::guard('investigator')->user();
        $deletedInvestigatorId = $investigator->id;
        $deletedFullName = $investigator->full_name;
        $deletedBadge = $investigator->badge_number;

        if ($investigator->profile_image) {
            Storage::disk('public')->delete($investigator->profile_image);
        }

        $investigator->delete();

        InvestigatorNotification::notifyActiveInvestigators([
            'created_by_investigator_id' => $actor?->id,
            'type' => 'system',
            'priority' => 'high',
            'title' => 'Investigator Account Deleted',
            'message' => $deletedFullName . ' (Badge #' . $deletedBadge . ') account has been deleted.',
            'action_url' => route('investigator.account.page'),
        ], [$deletedInvestigatorId]);

        AuditTrailLog::record([
            'investigator_id' => $actor?->id,
            'action_type' => 'account_delete',
            'action_performed' => 'Deleted investigator account for ' . $deletedFullName . ' (Badge #' . $deletedBadge . ').',
        ]);

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'message' => 'Investigator account deleted successfully!'
            ]);
        }

        return redirect()->route('investigator.account.page')->with('success', 'Investigator account deleted successfully!');
    }
}
