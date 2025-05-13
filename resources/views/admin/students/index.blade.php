@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <!-- Search and Filter -->
<div class="mb-4 flex justify-between items-center">
    <form method="GET" action="{{ route('admin.students') }}" class="flex space-x-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email"
               class="border border-gray-300 rounded px-3 py-1">

        <select name="status" class="border border-gray-300 rounded px-3 py-1">
            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
        </select>

        <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600">Filter</button>
    </form>
</div>

    <h2 class="text-2xl font-bold mb-6">📘 All Students</h2>

    <table class="w-full table-auto border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left">Full Name</th>
                <th class="px-4 py-2 text-left">Email</th>
                <th class="px-4 py-2 text-left">ID Number</th>
                <th class="px-4 py-2 text-left">Approval Status</th>
                <th class="px-4 py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($students as $student)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $student->full_name }}</td>
                    <td class="px-4 py-2">{{ $student->email }}</td>
                    <td class="px-4 py-2">{{ $student->id_number }}</td>
                    <td class="px-4 py-2">
                        {{ $student->status }}
                        {{-- {{ $student->approved ? 'Approved' : 'Pending' }} --}}
                    </td>
                    <td class="px-4 py-2 space-x-2">
                        @if (!$student->status === 'approved')
                            <form action="{{ route('admin.approve', $student->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-green-600 hover:underline">Approve</button>
                            </form>
                        @endif

                        <form action="{{ route('admin.deleteStudent', $student->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this student?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">No students found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
