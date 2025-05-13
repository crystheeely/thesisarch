<!-- resources/views/profile/partials/thesis-list.blade.php -->

<table class="min-w-full bg-white border-collapse border border-gray-200">
    <thead>
        <tr>
            <th class="border px-4 py-2">Thesis Title</th>
            <th class="border px-4 py-2">Status</th>
            <th class="border px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($theses as $thesis)
            <tr>
                <td class="border px-4 py-2">{{ $thesis->title }}</td>
                <td class="border px-4 py-2">{{ $thesis->status }}</td>
                <td class="border px-4 py-2">
                    <!-- Add buttons or links for actions like edit, view, etc. -->
                    <a href="{{ route('theses.show', $thesis->id) }}" class="text-blue-600 hover:underline">View</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
