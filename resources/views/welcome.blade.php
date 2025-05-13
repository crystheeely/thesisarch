@extends('layouts.app')

@section('content')
    <section class="text-center mt-20 px-4">
        <h1 class="text-4xl md:text-5xl font-bold text-blue-800">Welcome to the Thesis Repository</h1>
        <p class="text-blue-600 mt-4 text-lg">Explore, submit, and manage academic works from students and faculty.</p>

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-center space-x-4">
            <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-2 rounded-xl text-lg hover:bg-blue-500 transition">
                Upload Thesis
            </a>
            <a href="{{ route('login') }}" class="border border-blue-600 text-blue-600 px-6 py-2 rounded-xl text-lg hover:bg-blue-50 transition">
                Browse Theses
            </a>
        </div>

        <div class="mt-8 bg-white shadow-md rounded-lg p-4 mb-6">
            <h3 class="text-xl font-semibold mb-4">Approved Thesis</h3>
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
    </section>
@endsection
