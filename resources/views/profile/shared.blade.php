@extends('layouts.app')

@section('content')
<main class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">
        {{ $user->role === 'admin' ? 'Admin Profile' : 'Student Profile' }}
    </h1>

    <!-- Common Info -->
    <div>
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
    </div>

    <!-- Role-Specific Sections -->
    @if ($user->role === 'student')
        <h2 class="mt-6 text-xl font-semibold">My Theses</h2>
        @forelse ($theses as $thesis)
            <div>{{ $thesis->title }} ({{ $thesis->status }})</div>
        @empty
            <p>No theses yet.</p>
        @endforelse

        <h2 class="mt-6 text-xl font-semibold">My Reports</h2>
        @forelse ($reports as $report)
            <div>{{ $report->original_name }}</div>
        @empty
            <p>No reports yet.</p>
        @endforelse
    @elseif ($user->role === 'admin')
        <h2 class="mt-6 text-xl font-semibold">All Theses</h2>
        @forelse ($theses as $thesis)
            <div>{{ $thesis->title }} by {{ $thesis->user->name ?? 'Unknown' }}</div>
        @empty
            <p>No theses available.</p>
        @endforelse
    @endif
</main>
@endsection
