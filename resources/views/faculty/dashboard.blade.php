@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

    <!-- Welcome Section -->
    <div class="bg-blue-100 p-6 rounded-lg shadow-md mb-6 text-center">
        <h2 class="text-3xl font-bold text-blue-900">
            Welcome back, Adviser {{ Auth::user()->full_name }}!
        </h2>
        <p class="text-gray-700 text-lg">Continue exploring academic research</p>
    </div>

    <!-- Pending Student Approvals -->
    <div class="bg-white shadow-md rounded-lg p-4 mb-6">
        <h3 class="text-xl font-semibold mb-4">Pending Student Approvals</h3>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <table class="table-auto w-full bg-white rounded shadow">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="p-2">Name</th>
                    <th class="p-2">Email</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pendingStudents as $student)
                    <tr>
                        <td class="p-2">{{ $student->full_name }}</td>
                        <td class="p-2">{{ $student->email }}</td>
                        <td class="p-2">
                            {{ $student->status === 'approved' ? 'Approved' : 'Pending' }}
                        </td>
                        <td class="p-2 space-x-2">
                        @if($student->status !== 'approved')
                                <form action="{{ route('faculty.approve', $student->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-green-600 hover:underline">Approve</button>
                                </form>
                            @else
                                <span class="text-gray-500">Already Approved</span>
                            @endif

                            <form action="{{ route('faculty.deleteStudent', $student->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-gray-500 p-4">No pending students.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Thesis Overview -->
    <div class="bg-white shadow-md rounded-lg p-4 mb-6">
        <h3 class="text-xl font-semibold mb-4">Thesis Overview</h3>

        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">Title</th>
                    <th class="border p-2">Author</th>
                    <th class="border p-2">Date Submitted</th>
                    <th class="border p-2">Status</th>
                    <th class="border p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($theses as $thesis)
                    <tr class="text-center">
                        <td class="border p-2">
                            <a href="{{ route('theses.show', $thesis->id) }}" target="_blank" class="text-blue-600 hover:underline">
                                {{ $thesis->title }}
                            </a>
                        </td>
                        <td class="border p-2">{{ $thesis->user->full_name }}</td>
                        <td class="border p-2">{{ $thesis->created_at->format('M d, Y') }}</td>
                        <td class="border p-2">
                            <span class="px-2 py-1 rounded-lg 
                                {{ $thesis->status == 'approved' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }}">
                                {{ ucfirst($thesis->status) }}
                            </span>
                        </td>
                        <td class="border p-2 space-x-2">
                            <a href="{{ route('theses.show', $thesis->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-700">View</a>

                            @if($thesis->status == 'pending')
                                <form action="{{ route('theses.approve', $thesis->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-700">Approve</button>
                                </form>
                                <form action="{{ route('theses.revise', $thesis->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-700">Revise</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="border p-4 text-center text-gray-500">No theses available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $theses->links() }}
        </div>
    </div>

    <!-- Dashboard Statistics -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4">📊 Dashboard Statistics</h2>

        <table class="w-full table-auto">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="px-4 py-2">Category</th>
                    <th class="px-4 py-2">Count</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b">
                    <td class="px-4 py-2">
                        <a href="{{ route('admin.students') }}" class="text-blue-600 hover:underline">Total Students</a>
                    </td>
                    <td class="px-4 py-2">{{ $totalStudents }}</td>
                </tr>
                <tr class="border-b">
                    <td class="px-4 py-2">
                        <a href="{{ route('theses.index', ['status' => 'approved']) }}" class="text-blue-600 hover:underline">Completed Theses</a>
                    </td>
                    <td class="px-4 py-2">{{ $completedTheses }}</td>
                </tr>
                <tr class="border-b">
                    <td class="px-4 py-2">
                        <a href="{{ route('theses.index', ['status' => 'pending']) }}" class="text-blue-600 hover:underline">Pending Theses</a>
                    </td>
                    <td class="px-4 py-2">{{ $pendingTheses }}</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
@endsection
