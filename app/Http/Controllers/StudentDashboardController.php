<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Thesis;
use App\Models\Notification;

class StudentDashboardController extends Controller
{
    // Apply the auth and approved middleware to this controller
    public function __construct()
    {
        $this->middleware('auth'); // Ensure the user is logged in
    }

    // Check the dashboard visibility based on role and approval status
    public function showDashboard()
    {
        $user = Auth::user();
        
        // Make sure the user is a student and is approved
        if ($user->role !== 'student') {
            abort(403, 'Unauthorized access.');
        }
        
        if (!$user->status == 'approved') {
            return redirect()->route('student.awaiting-approval');
        }

        // Fetch necessary data for the dashboard
        $totalStudents = User::where('role', 'student')->count();
        $graduates = User::where('status', 'graduated')->count();
        $completedTheses = Thesis::where('status', 'Approved')->whereNotNull('user_id')->count();
        $pendingTheses = Thesis::where('status', 'Pending')->count();

        //show thesis to other if the status is approved
        $theses = Thesis::with('comments') // eager load comments for each thesis
            ->where('user_id', $user->id)
            ->paginate(10);

        // Fetch latest notifications for this student
        $notifications = $user->notifications()->take(10)->latest()->get();

        // Pass all data to the dashboard view
        return view('student.dashboard', compact(
            'totalStudents', 
            'graduates', 
            'completedTheses', 
            'pendingTheses', 
            'theses', 
            'notifications'
        ));
    }
}
