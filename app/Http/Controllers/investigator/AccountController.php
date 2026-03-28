<?php

namespace App\Http\Controllers\investigator;

use App\Http\Controllers\Controller;
use App\Models\Investigator;
use Illuminate\Http\Request;
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

        Investigator::create($validated);

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'message' => 'Investigator account created successfully!'
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
        if ($investigator->profile_image) {
            Storage::disk('public')->delete($investigator->profile_image);
        }

        $investigator->delete();

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'message' => 'Investigator account deleted successfully!'
            ]);
        }

        return redirect()->route('investigator.account.page')->with('success', 'Investigator account deleted successfully!');
    }
}
