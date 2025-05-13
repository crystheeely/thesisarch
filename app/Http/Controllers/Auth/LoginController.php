<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
{
    // Validate input
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Check if user exists and is pending
    $user = User::where('email', $credentials['email'])->first();

    if ($user && $user->status === 'pending') {
        throw ValidationException::withMessages([
            'email' => 'Your account is pending approval.',
        ]);
    }

    // Attempt login
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $user = Auth::user();

        // ✅ Redirect based on role and status
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'student' && $user->status === 'approved') {
            return redirect()->route('student.dashboard');
        }

        // Default fallback
        Auth::logout();
        return redirect()->route('login')->with('error', 'Access denied.');
    }

    // Invalid credentials
    throw ValidationException::withMessages([
        'email' => 'The provided credentials are incorrect.',
    ]);
}

protected function redirectTo()
{
    if (auth()->user()->role === 'admin') {
        return '/admin/dashboard';
    }

    if (auth()->user()->role === 'faculty') {
        return '/faculty/dashboard';
    }

    if (auth()->user()->role === 'student') {
        return '/student/dashboard';
    }

    // Default fallback
    return '/';
}



}