<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Thesis;
use App\Models\Report;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }

    /**
     * Display the user's profile form.
     */
    public function edit()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to view your profile.');
        }

        $theses = Thesis::where('user_id', $user->id)->get();
        $reports = Report::where('user_id', $user->id)->get();

        return view('profile.edit', compact('user', 'theses', 'reports'));
    }

    public function studentProfile()
    {
        $user = auth()->user();

        if ($user->role !== 'student') {
            abort(403);
        }

        $theses = Thesis::where('user_id', $user->id)->get();
        $reports = Report::where('user_id', $user->id)->get();

        return view('profile.shared', compact('user', 'theses', 'reports'));
    }

    public function adminProfile()
    {
        $user = auth()->user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        $theses = Thesis::all(); // or admin-specific data
        $reports = collect();    // empty collection if unused

        return view('profile.shared', compact('user', 'theses', 'reports'));
    }


    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();

        // Store new photo
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');

            // Optionally delete the old photo if stored
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Update user's photo path
            $user->profile_photo_path = $path;
            $user->save();
        }

        return back()->with('success', 'Profile photo updated.');
    }
    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function editPersonal()
    {
        $user = auth()->user();
        return view('profile.edit-personal', compact('user'));
    }
    
    public function updatePersonal(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        //'email' => 'required|email|unique:users,email,' . auth()->id(),
        'school' => 'nullable|string|max:255',
        'course' => 'nullable|string|max:255',
        'year' => 'nullable|string|max:10',
        'birthdate' => 'nullable|date',
        'address' => 'nullable|string|max:255',
    ]);

    $user = auth()->user();
    $user->update($validated);

    return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
}

    
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
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
