<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Show the authenticated user's profile (read-only view with edit entry point).
     */
    public function show(Request $request): Response
    {
        $userId = Auth::id();
        $user = $userId ? User::find($userId) : null;

        return Inertia::render('Profile/Index', [
            'user' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
                // Optional fields if present on the model
                'status' => $user->getAttribute('status'),
                'last_login_at' => $user->getAttribute('last_login_at'),
            ] : null,
            'status' => session('status'),
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $authenticatedUser = User::findOrFail(Auth::id());

        $validated = $request->validated();

        $authenticatedUser->fill([
            'name' => $validated['name'] ?? $authenticatedUser->name,
            'email' => $validated['email'] ?? $authenticatedUser->email,
        ]);

        if ($authenticatedUser->isDirty('email')) {
            $authenticatedUser->email_verified_at = null;
        }

        if (!empty($validated['password'])) {
            $authenticatedUser->password = Hash::make($validated['password']);
        }

        $authenticatedUser->save();

        return Redirect::route('profile.show')->with('success', __('Profile updated successfully.'));
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
