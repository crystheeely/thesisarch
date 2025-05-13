<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Thesis;
use Illuminate\Support\Facades\Auth;

class FacultyDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
{
    if (Auth::user()->role !== 'faculty') {
        abort(403, 'Unauthorized access - Faculty only');
    }

    $totalStudents = User::where('role', 'student')->count();
    $graduates = User::where('role', 'student')->where('status', 'graduated')->count();
    $completedTheses = Thesis::where('status', 'Approved')->count();
    $pendingTheses = Thesis::where('status', 'Pending')->count();
    $pendingStudents = User::where('role', 'student')->where('status', 'pending')->get();
    $pendingStudentCount = $pendingStudents->count();
    $theses = Thesis::latest()->paginate(10);

    return view('faculty.dashboard', compact(
        'totalStudents',
        'graduates',
        'completedTheses',
        'pendingTheses',
        'pendingStudents',
        'theses'
    ));
}

public function approveStudent($id)
{
    $student = User::findOrFail($id);
    $student->status = 'approved';
    $student->save();

    return redirect()->route('faculty.dashboard')->with('success', 'Student approved successfully.');
}
public function deleteStudent($id)
{
    $student = User::findOrFail($id);

    // Optional: check if the user is actually a student
    if ($student->role !== 'student') {
        return redirect()->route('faculty.dashboard')->with('error', 'Only students can be deleted.');
    }

    $student->delete();

    return redirect()->route('faculty.dashboard')->with('success', 'Student deleted successfully.');
}


}
