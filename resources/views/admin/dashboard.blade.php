@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

    <!-- Welcome Header -->
    <div class="bg-blue-100 p-6 rounded-lg shadow-md mb-6 text-center">
        <h2 class="text-3xl font-bold text-blue-900">Welcome back, {{ Auth::user()->full_name }}!</h2>
        <p class="text-gray-700 text-lg">Manage users and theses efficiently</p>
    </div>

    <!-- Create Student Modal Trigger -->
    <div class="flex justify-end mb-4">
        <button onclick="openModal()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Create Student Account
        </button>
    </div>


    <!-- New Student Modal -->
<div id="createStudentModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-lg">
        <h2 class="text-xl font-bold mb-4">Create New Student Account</h2>
        <form action="{{ route('admin.students.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block mb-1">Full Name</label>
                <input type="text" name="full_name" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1">ID Number</label>
                <input type="text" name="id_number" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Email</label>
                <input type="email" name="email" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Password</label>
                <input type="password" name="password" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full border rounded p-2" required>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeModal()" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('createStudentModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('createStudentModal').classList.add('hidden');
    }
</script>


    <!-- Registered Users Table -->
<div class="bg-white shadow-md rounded-lg p-4 mb-6">
    <h3 class="text-xl font-semibold mb-4">Registered Students</h3>
    <table class="min-w-full table-auto">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="px-4 py-2">Full Name</th>
                <th class="px-4 py-2">ID Number</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Role</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($students as $student)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $student->full_name }}</td>
                    <td class="px-4 py-2">{{ $student->id_number }}</td>
                    <td class="px-4 py-2">{{ $student->email }}</td>
                    <td class="px-4 py-2">{{ ucfirst($student->role) }}</td>
                    <td class="px-4 py-2">
                        <span class="{{ $student->approved ? 'text-green-700' : 'text-yellow-700' }}">
                            {{ $student->approved ? 'Approved' : 'Pending' }}
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        <form action="{{ route('admin.users.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-gray-500 py-4">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Faculty Users Table -->
<div class="bg-white shadow-md rounded-lg p-4 mb-6">
    <h3 class="text-xl font-semibold mb-4">Registered Faculty</h3>
    <table class="min-w-full table-auto">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="px-4 py-2">Full Name</th>
                <th class="px-4 py-2">ID Number</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($faculty as $user)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $user->full_name }}</td>
                    <td class="px-4 py-2">{{ $user->id_number }}</td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2">
                        <span class="{{ $user->approved ? 'text-green-700' : 'text-yellow-700' }}">
                            {{ $user->approved ? 'Approved' : 'Pending' }}
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-gray-500 py-4">No faculty found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

    <!-- Thesis Submissions Table -->
    <div class="bg-white shadow-md rounded-lg p-4 mb-6">
        <h3 class="text-xl font-semibold mb-4">Thesis Submissions</h3>
        <table class="min-w-full table-auto">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="px-4 py-2">Title</th>
                    <th class="px-4 py-2">Author</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Semester</th>
                    <th class="px-4 py-2">Month</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($theses as $thesis)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $thesis->title }}</td>
                        <td class="px-4 py-2">{{ $thesis->user->full_name }}</td>
                        <td class="px-4 py-2">
                            <span class="{{ $thesis->status == 'approved' ? 'text-green-700' : ($thesis->status == 'pending' ? 'text-yellow-700' : 'text-red-700') }}">
                                {{ ucfirst($thesis->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2">{{ $thesis->semester }}</td>
                        <td class="px-4 py-2">{{ $thesis->month }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('theses.show', $thesis->id) }}" class="text-blue-600 hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500 py-4">No thesis submissions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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
                    <a href="{{ route('admin.faculty') }}" class="text-blue-600 hover:underline">Total Faculty</a>
                    </td>
                    <td class="px-4 py-2">{{ $totalFaculty }}</td>
                </tr>
                <tr class="border-b">
                    <td class="px-4 py-2">
                        <a href="{{ route('admin.students') }}" class="text-blue-600 hover:underline">Total Students</a>
                    </td>
                    <td class="px-4 py-2">{{ $totalStudents }}</td>
                </tr>
                <tr class="border-b">
                    <td class="px-4 py-2">
                        <a href="{{ route('admin.students.graduated') }}" class="text-blue-600 hover:underline">Graduated Students</a>
                    </td>
                    <td class="px-4 py-2">{{ $graduates }}</td>
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
