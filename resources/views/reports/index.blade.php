@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Reports</h1>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success bg-green-500 text-white p-4 rounded-md mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Create Report Button -->
    <div class="mb-6 flex justify-end">
        <a href="{{ route('reports.create') }}" class="bg-blue-600 text-white py-2 px-6 rounded-md shadow-md hover:bg-blue-700 transition duration-200">Create Report</a>
    </div>

    <!-- Report Table -->
    <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
        <table class="w-full table-auto border-collapse text-sm">
            <thead class="bg-blue-100">
                <tr>
                    <th class="border p-3 text-left text-gray-600">Title</th>
                    <th class="border p-3 text-left text-gray-600">Author</th>
                    <th class="border p-3 text-left text-gray-600">Year</th>
                    <th class="border p-3 text-left text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($theses as $thesis)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="border p-3">{{ $thesis->title }}</td>
                        <td class="border p-3">{{ $thesis->user->name }}</td>
                        <td class="border p-3">{{ $thesis->created_at->year }}</td>
                        <td class="border p-3">
                            <!-- Actions (e.g., download) -->
                            <a href="{{ route('reports.download', ['report' => $thesis->id, 'field' => 'pdf']) }}" class="text-blue-600 hover:underline">
                                <i class="fas fa-download"></i> Download PDF
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="border p-3 text-center text-gray-500">No reports found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="mt-6">
        {{ $theses->links() }}
    </div>
</div>
@endsection
