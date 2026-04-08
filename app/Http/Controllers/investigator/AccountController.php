<?php

namespace App\Http\Controllers\investigator;

use App\Http\Controllers\Controller;
use App\Models\AuditTrailLog;
use App\Models\Investigator;
use App\Models\InvestigatorNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
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
            $validated['profile_image'] = $this->storeProfileImageToPublic($request->file('profile_image'));
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
                $this->deleteProfileImage($investigator->profile_image);
            }

            $validated['profile_image'] = $this->storeProfileImageToPublic($request->file('profile_image'));
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
                'profile_image_url' => $investigator->profile_image ? $this->resolveProfileImageUrl($investigator->profile_image) : null,
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
            $this->deleteProfileImage($investigator->profile_image);
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

    private function storeProfileImageToPublic($file): string
    {
        $directory = public_path('investigators');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = $file->hashName();
        $file->move($directory, $filename);

        return 'investigators/' . $filename;
    }

    private function resolveProfileImageUrl(string $path): string
    {
        $cleanPath = ltrim(trim($path), '/');
        if ($cleanPath === '') {
            return '';
        }

        if (str_starts_with($cleanPath, 'http://') || str_starts_with($cleanPath, 'https://')) {
            return $cleanPath;
        }

        if (File::exists(public_path($cleanPath))) {
            return asset($cleanPath);
        }

        return asset('storage/' . $cleanPath);
    }

    private function deleteProfileImage(string $path): void
    {
        $cleanPath = ltrim(trim($path), '/');
        if ($cleanPath === '') {
            return;
        }

        $publicFile = public_path($cleanPath);
        if (File::exists($publicFile)) {
            File::delete($publicFile);
        }

        $legacyFile = storage_path('app/public/' . $cleanPath);
        if (File::exists($legacyFile)) {
            File::delete($legacyFile);
        }
    }
}
