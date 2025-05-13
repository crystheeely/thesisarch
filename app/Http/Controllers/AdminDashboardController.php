<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Thesis;
use Illuminate\Support\Facades\Auth; 

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $students = User::where('role', 'student')->get();
        $faculty = User::where('role', 'faculty')->get();
        $users = $students->merge($faculty);

        $totalStudents = $students->count();
        //$graduates = User::where('role', 'student')->where('graduated', true)->count();
        $graduates = 0;
        $completedTheses = \App\Models\Thesis::where('status', 'approved')->count();
        $pendingTheses = \App\Models\Thesis::where('status', 'pending')->count();
        $totalFaculty = $faculty->count();
        $theses = \App\Models\Thesis::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'users',
            'students',
            'faculty',
            'theses',
            'totalStudents',
            'graduates',
            'completedTheses',
            'pendingTheses',
            'totalFaculty'
        ));
    }

    public function facultyList()
    {
        $faculty = User::where('role', 'faculty')->get();
        return view('admin.faculty.index', compact('faculty'));
    }

    public function studentList()
    {
        dd('ssss');
        $students = User::where('role', 'student')->get();
        return view('admin.students.index', compact('students'));
    }

    public function createStudent()
    {
        return view('admin.students.create');
    }

    public function storeStudent(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'id_number' => 'required|string|max:255|unique:users,id_number',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'full_name' => $validated['full_name'],
            'id_number' => $validated['id_number'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'student',
            'approved' => true,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Student account created successfully.');
    }

    public function approveStudent($id)
    {
        $student = User::findOrFail($id);
        $student->status = 'approved';
        $student->approved = true;
        $student->save();

        return response()->json(['message' => 'Student approved successfully.']);
    }

    public function students(Request $request)
    {
        $query = User::where('role', 'student');
    
        // Filter by approval status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('approved', $request->status === 'approved');
        }
    
        // Search by name or email
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('full_name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }
    
        $students = $query->get();
    
        return view('admin.students.index', compact('students'));
    }
    
    public function graduatedStudents()
    {
        $students = User::where('role', 'student')
                        ->where('status', 'graduated')
                        ->get();

        $totalStudents = User::where('role', 'student')->count();
        $totalFaculty = User::where('role', 'faculty')->count();
        //$graduates = User::where('status', 'graduated')->count();
        $graduates = 0;
        $completedTheses = Thesis::where('status', 'Approved')->count();
        $pendingTheses = Thesis::where('status', 'Pending')->count();

        return view('admin.students.graduated', compact(
             'totalFaculty',
             'students',
            'totalStudents',
            'graduates',
            'completedTheses',
            'pendingTheses'
        ));
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }


    public function deleteStudent($id)
    {
        $student = User::findOrFail($id);
        $student->delete();

        return redirect()->back()->with('success', 'Student deleted successfully.');
    }
}
