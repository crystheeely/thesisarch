<!-- resources/views/profile/partials/report-list.blade.php -->

<div class="mt-4">
    <h3 class="font-bold text-lg">Your Reports</h3>

    @if ($reports->isEmpty())
        <p>No reports available.</p>
    @else
        <ul class="space-y-4">
            @foreach ($reports as $report)
                <li class="bg-gray-100 p-4 rounded-lg shadow-sm">
                    <p><strong>Title:</strong> {{ $report->title }}</p>
                    <p><strong>Created At:</strong> {{ $report->created_at->format('M d, Y') }}</p>
                    <p><strong>Status:</strong> {{ $report->status }}</p>
                    <a href="{{ route('reports.download', $report->id) }}" class="text-blue-600 hover:underline">Download</a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
