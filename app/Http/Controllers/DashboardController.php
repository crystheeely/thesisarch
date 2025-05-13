<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    $user = Auth::user();

    if ($user->isAdmin()) {
        $pendingStudents = User::where('role', 'student')->where('is_approved', false)->get();
        $theses = Thesis::latest()->paginate(10);
        $totalStudents = User::where('role', 'student')->count();
        $graduates = User::where('role', 'student')->where('is_graduated', true)->count();
        $completedTheses = Thesis::where('status', 'approved')->count();
        $pendingTheses = Thesis::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'pendingStudents', 'theses',
            'totalStudents', 'graduates',
            'completedTheses', 'pendingTheses'
        ));
    }

    if ($user->isStudent()) {
        $approvedTheses = Thesis::where('status', 'approved')->get();
        return view('student.dashboard', compact('approvedTheses'));
    }

    abort(403); // Unauthorized role
}

    
public function studentDashboard()
{
    $user = auth()->user();

    if (!$user->is_approved) {
        return view('dashboard.awaiting-approval');
    }

    $notifications = $user->notifications()->latest()->get(); // Adjust based on your relationship
    $theses = $user->theses()->latest()->paginate(10);        // Eloquent relationship assumed

    return view('dashboard.student', compact('notifications', 'theses'));
}

}
