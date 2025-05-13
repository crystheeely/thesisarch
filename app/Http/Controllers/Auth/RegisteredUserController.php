<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Mail\AdminApprovalNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;



class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    public function showRegistrationForm()
    {
    return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'full_name' => ['required', 'string', 'max:255'],
        'id_number' => [
            'required',
            function ($attribute, $value, $fail) use ($request) {
                if ($request->role === 'faculty') { 
                    if (!preg_match('/^\d{4}-\d{3}$/', $value)) {
                        $fail('Faculty ID must be in the format YYYY-NNN (e.g., 2012-123).');
                    }
                } elseif ($request->role === 'student') { 
                    if (!preg_match('/^\d{4}-\d{4}$/', $value)) {
                        $fail('Student ID must be in the format YYYY-NNNN (e.g., 2013-1234).');
                    }
                }
            }
        ],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'role' => 'required|in:student,faculty',
    ]);

    if (User::where('id_number', $request->id_number)->exists()) {
        throw ValidationException::withMessages([
            'id_number' => 'The ID number is already registered.',
        ]);
    }
    
    $status = $request->role === 'faculty' ? 'approved' : 'pending';
// dd($request->role,$status );
    $user = User::create([
       // 'name' => $request->full_name,         // Required by default Laravel schema
        'full_name' => $request->full_name,    // Your own additional field
        'id_number' => $request->id_number,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'status' => $status
    ]);
    $message = 'Registration successful.';
    if(  $status === 'pending' ) {
        $message = 'Registration successful. Your account is pending approval.';
    }

    event(new Registered($user));

    // 🚀 Send admin an email notification for approval
    Mail::to('admin@example.com')->send(new AdminApprovalNotification($user));

    return redirect()->intended(route('login'))->with('success',  $message );

}

}