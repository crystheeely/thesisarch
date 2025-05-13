@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

    <!-- Welcome Section -->
    <div class="bg-blue-100 p-6 rounded-lg shadow-md mb-6 text-center">
        <h2 class="text-3xl font-bold text-blue-900">Welcome back, {{ Auth::user()->full_name }}!</h2>
        <p class="text-gray-700 text-lg">Continue exploring academic research</p>
    </div>

    <!-- Notification Section -->
<div class="bg-white shadow-md rounded-lg p-4 mb-6">
    <h3 class="text-xl font-semibold mb-4">📬 Notifications</h3>

    @if ($notifications->isEmpty())
        <p class="text-gray-600">No notifications yet.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full min-w-[600px]">
                <thead>
                    <tr class="text-left text-gray-700 bg-gray-100">
                        <th class="p-2">Message</th>
                        <th class="p-2">Date</th>
                        <th class="p-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($notifications as $note)
                        <tr class="border-t">
                            <td class="p-2">{{ $note->data['message'] }}</td>
                            <td class="p-2 text-sm text-gray-500">{{ $note->created_at->diffForHumans() }}</td>
                            <td class="p-2">
                                @if ($note->data['thesis_id'])
                                    <a href="{{ route('theses.show', $note->data['thesis_id']) }}" class="text-blue-600 hover:underline">View Thesis</a>
                                @else
                                    <span class="text-gray-500 italic">No Thesis</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>


    <!-- Upload Button -->
    <div class="flex justify-end mb-4">
        <a href="{{ route('theses.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Upload Thesis
        </a>
    </div>

    <!-- Thesis Table -->
    <div class="bg-white shadow-md rounded-lg p-4">
        <h3 class="text-xl font-semibold mb-4">My Thesis</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full min-w-[600px] border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200 text-left">
                        <th class="border p-2">Title</th>
                        <th class="border p-2">Date Submitted</th>
                        <th class="border p-2">Status</th>
                        <th class="border p-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($theses as $thesis)
                        <tr class="border-t">
                            <td class="border p-2">
                                <a href="{{ route('theses.show', $thesis->id) }}" target="_blank" class="text-blue-600 hover:underline">
                                    {{ $thesis->title }}
                                </a>
                            </td>
                            <td class="border p-2">{{ $thesis->created_at->format('Y-m-d') }}</td>
                            <td class="border p-2">
                                @if ($thesis->status === \App\Models\Thesis::STATUS_APPROVED)
                                    <span class="text-green-600 font-semibold">Approved</span>
                                @elseif ($thesis->status === \App\Models\Thesis::STATUS_PENDING)
                                    <span class="text-yellow-600 font-semibold">Pending</span>
                                @elseif ($thesis->status === \App\Models\Thesis::STATUS_REVISED)
                                    <span class="text-red-600 font-semibold">Needs Revision</span>
                                @else
                                    <span class="text-gray-600">{{ ucfirst($thesis->status) }}</span>
                                @endif
                            </td>
                            <td class="border p-2 flex space-x-2">
                                <a href="{{ route('theses.edit', $thesis->id) }}" class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500">Edit</a>
                                <form action="{{ route('theses.destroy', $thesis->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this thesis?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $theses->links() }}
        </div>
    </div>
</div>
@endsection
