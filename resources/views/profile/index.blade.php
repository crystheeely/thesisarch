@extends('layouts.app')

@section('content')
<div class="flex gap-6">

    <!-- Left: Personal Info (1/4) -->
    <div class="w-1/4 bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4 text-blue-700">Personal Information</h2>
        <table class="text-sm text-gray-700 w-full">
            <tr><td class="font-semibold">Name:</td><td>{{ Auth::user()->full_name }}</td></tr>
            <tr><td class="font-semibold">Email:</td><td>{{ Auth::user()->email }}</td></tr>
            <tr><td class="font-semibold">Birthdate:</td><td>{{ Auth::user()->birthdate }}</td></tr>
            <tr><td class="font-semibold">ID Number:</td><td>{{ Auth::user()->id_number }}</td></tr>
            <tr><td class="font-semibold">Course:</td><td>{{ Auth::user()->course }}</td></tr>
            <tr><td class="font-semibold">Year:</td><td>{{ Auth::user()->year }}</td></tr>
            <tr><td class="font-semibold">School:</td><td>{{ Auth::user()->school }}</td></tr>
            <tr><td class="font-semibold">Address:</td><td>{{ Auth::user()->address }}</td></tr>
        </table>
    </div>

    <!-- Right: Works & Reports (3/4) -->
    <div class="w-3/4 flex flex-col gap-6">

        <!-- Section 1: Works / Theses -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4 text-blue-700">Your Works</h2>
            <table class="w-full text-sm text-left border border-gray-200">
                <thead class="bg-blue-100 text-blue-800">
                    <tr>
                        <th class="p-2">Title</th>
                        <th class="p-2">Status</th>
                        <th class="p-2">Date Submitted</th>
                        <th class="p-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($theses as $thesis)
                    <tr class="border-t">
                        <td class="p-2">{{ $thesis->title }}</td>
                        <td class="p-2">{{ $thesis->status }}</td>
                        <td class="p-2">{{ $thesis->created_at->format('M d, Y') }}</td>
                        <td class="p-2">
                            <a href="{{ route('theses.show', $thesis->id) }}" class="text-blue-600 hover:underline">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Section 2: Reports -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-blue-700">Reports</h2>
                <a href="{{ route('reports.upload') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Add Report</a>
            </div>
            <table class="w-full text-sm text-left border border-gray-200">
                <thead class="bg-blue-100 text-blue-800">
                    <tr>
                        <th class="p-2">Title</th>
                        <th class="p-2">Date</th>
                        <th class="p-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                    <tr class="border-t">
                        <td class="p-2">{{ $report->title }}</td>
                        <td class="p-2">{{ $report->created_at->format('M d, Y') }}</td>
                        <td class="p-2">
                            <a href="{{ asset('storage/' . $report->file_path) }}" target="_blank" class="text-blue-600 hover:underline">Download</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
