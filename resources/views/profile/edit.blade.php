@extends('layouts.app')

@section('content')
<main class="container mx-auto py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ url()->previous() }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            <span class="font-medium">Back</span>
        </a>
    </div>

    <div class="flex gap-8">
        <!-- Left: Personal Info -->
        <aside class="w-1/4 bg-white p-6 shadow-lg rounded-lg">
            <h2 class="text-2xl font-semibold text-gray-700 mb-6">Personal Info</h2>

            @php
                $user = Auth::user();
            @endphp
            @if ($user)
                {{ $user->id }}
            @endif


            <!-- Profile Photo -->
            <div class="mb-6">
                <div class="text-sm font-semibold mb-2">Profile Photo</div>
                <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('default-avatar.png') }}" alt="Profile Photo" class="w-24 h-24 rounded-full mb-4">
                <form action="{{ route('profile.updatePhoto') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="profile_photo" class="mb-4 text-blue-500">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Update Photo</button>
                </form>
            </div>

            <!-- Info List -->
            <div class="text-sm space-y-4">
                <div><strong>Name:</strong> {{ $user?->name }}</div>
                <div><strong>Email:</strong> {{ $user?->email }}</div>
                <div><strong>School:</strong> {{ $user?->school ?? 'N/A' }}</div>
                <div><strong>Course:</strong> {{ $user?->course ?? 'N/A' }}</div>
                <div><strong>Year:</strong> {{ $user?->year ?? 'N/A' }}</div>
                <div><strong>Birthdate:</strong> {{ $user?->birthdate ?? 'N/A' }}</div>
                <div><strong>Address:</strong> {{ $user?->address ?? 'N/A' }}</div>
                <a href="{{ route('profile.editPersonal') }}" class="text-blue-500 hover:underline">✏️ Edit Info</a>
            </div>
        </aside>

        <!-- Right: Content -->
        <section class="w-3/4 bg-white p-6 shadow-lg rounded-lg space-y-10">
            <!-- Theses -->
            <div>
                <h2 class="text-2xl font-semibold text-gray-700 mb-6">My Theses</h2>
                <table class="min-w-full table-auto text-sm text-gray-700">
                    <thead>
                        <tr class="bg-blue-100">
                            <th class="px-4 py-2 text-left">Thesis Title</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($theses as $thesis)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $thesis->title }}</td>
                                <td class="px-4 py-2">{{ ucfirst($thesis->status) }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('theses.show', $thesis->id) }}" class="text-blue-500 hover:underline">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-2 text-gray-500 italic">No theses found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Reports -->
            <div>
                <h2 class="text-2xl font-semibold text-gray-700 mb-6">My Reports</h2>
                <table class="min-w-full table-auto text-sm text-gray-700 mb-4">
                    <thead>
                        <tr class="bg-blue-100">
                            <th class="px-4 py-2 text-left">Report Title</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $report->original_name }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ Storage::url($report->file_path) }}" class="text-blue-500 hover:underline" target="_blank">Download</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-4 py-2 text-gray-500 italic">No reports found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="text-right mt-6">
                    <a href="{{ route('reports.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md">Add New Report</a>
                </div>
            </div>
        </section>
    </div>
</main>
@endsection
