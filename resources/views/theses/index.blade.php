@extends('layouts.app')

@section('content')
<div class="container max-w-7xl mx-auto py-8">
    <h1 class="text-2xl font-semibold mb-6">Search Results</h1>

    {{-- Mobile Filter Toggle --}}
    <button class="md:hidden bg-blue-600 text-white px-4 py-2 rounded mb-4" onclick="toggleFilters()">
        Filters
    </button>

    <div class="flex gap-6">
        {{-- Sidebar (Filter Box) --}}
        <aside id="filterSidebar" class="w-40 bg-white border rounded-lg shadow p-3 min-h-screen hidden md:block">
            <h2 class="text-lg font-semibold mb-4">Filters</h2>

            <form method="GET" action="{{ route('theses.index') }}" class="space-y-4 text-sm">
                {{-- Author --}}
                
                <div>
                    <label for="author" class="block font-medium">Search</label>
                    {{-- <select name="author" id="author" class="mt-1 w-full border-gray-300 rounded">
                        <option value="">All</option>
                        @foreach ($authors as $id => $value)
                            <option value="{{ $value->id }}" {{ request('author') == $value->id ? 'selected' : '' }}>
                                {{ $value->full_name }}
                            </option>
                        @endforeach
                    </select> --}}
                    <input type="text" placeholder="Search..." name="search" class="mt-1 w-full border-gray-300 rounded">
                </div>

                <div>
                    <label for="author" class="block font-medium">Author</label>
                    <select name="author" id="author" class="mt-1 w-full border-gray-300 rounded">
                        <option value="">All</option>
                        @foreach ($authors as $id => $value)
                            <option value="{{ $value->id }}" {{ request('author') == $value->id ? 'selected' : '' }}>
                                {{ $value->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Display Co-authors -->
                @if (!empty($thesis->decoded_coauthors))
                    <ul>
                        @foreach ($thesis->decoded_coauthors as $coauthor)
                            <li>{{ $coauthor }}</li>
                        @endforeach
                    </ul>
                @else
                    <span>No co-authors</span>
                @endif

                {{-- Academic Year --}}
                <div>
                    <label for="academic_year" class="block font-medium">Year</label>
                    <select name="academic_year" id="academic_year" class="mt-1 w-full border-gray-300 rounded">
                        <option value="">All</option>
                        @foreach ($years as $year)
                            <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Semester --}}
                <div>
                    <label for="semester" class="block font-medium">Semester</label>
                    <select name="semester" id="semester" class="mt-1 w-full border-gray-300 rounded">
                        <option value="">All</option>
                        @foreach ($semesters as $semester)
                            <option value="{{ $semester }}" {{ request('semester') == $semester ? 'selected' : '' }}>{{ $semester }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Month --}}
                <div>
                    <label for="month" class="block font-medium">Month</label>
                    <select name="month" id="month" class="mt-1 w-full border-gray-300 rounded">
                        <option value="">All</option>
                        @foreach ($months as $month)
                            <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>{{ $month }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-4 flex justify-between">
                    <button type="submit"
                        class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                        Apply
                    </button>
                    <a href="{{ route('theses.index') }}"
                       class="text-xs text-gray-600 hover:underline">
                        Reset
                    </a>
                </div>
            </form>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 p-2">
            {{-- Thesis List --}}
            @forelse ($theses as $thesis)
                <div class="mb-8 border-b pb-4">
                    <a href="{{ route('theses.show', $thesis->id) }}" class="text-xl text-blue-600 font-medium hover:underline">
                        {{ $thesis->title }}
                    </a>

                    <p class="text-sm text-gray-700 mt-1">
                        {{ $thesis->author_name }}
                        @if(!empty($thesis->decoded_coauthors) && is_iterable($thesis->decoded_coauthors))
                            &nbsp;with {{ collect($thesis->decoded_coauthors)->filter()->implode(', ') }}
                        @endif
                    </p>

                    <p class="text-gray-600 text-sm mt-2">
                        {{ Str::limit($thesis->abstract, 300) }}
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        <strong>Keywords:</strong> {{ $thesis->keywords }} <br>
                        <strong>Semester:</strong> {{ $thesis->semester }} |
                        <strong>Year:</strong> {{ $thesis->academic_year }}
                    </p>

                    @if(auth()->user()->isAdmin())
                        @php
                            $statusColors = [
                                'approved' => 'green',
                                'pending' => 'yellow',
                                'revised' => 'red',
                            ];
                            $color = $statusColors[$thesis->status] ?? 'gray';
                        @endphp
                        <span class="inline-block mt-2 px-2 py-1 text-xs rounded-full bg-{{ $color }}-100 text-{{ $color }}-800">
                            {{ ucfirst($thesis->status) }}
                        </span>
                    @endif

                    @if($thesis->status === 'approved' && $thesis->qr_code)
                        <div class="mt-2 flex">
                            <img src="data:image/png;base64, {{ $thesis->qr_code }}" alt="QR Code" class="w-24 h-24 mr-4">
                            <div>
                                <p class="text-gray-600 text-sm mt-2">
                                    {{ Str::limit($thesis->abstract, 300) }}
                                </p>
                                {{-- Other thesis info --}}
                            </div>
                        </div>
                    @endif


                    <div class="mt-2 space-x-2">
                        <a href="{{ route('theses.download', $thesis->id) }}" class="text-sm text-blue-600 hover:underline">Download</a>
                        @if(auth()->id() === $thesis->user_id || auth()->user()->isAdmin())
                            <a href="{{ route('theses.edit', $thesis->id) }}" class="text-sm text-yellow-600 hover:underline">Edit</a>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-600">No theses found matching your criteria.</p>
            @endforelse

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $theses->appends(request()->query())->links() }}
            </div>
        </main>
    </div>
</div>

{{-- JS for Filter Toggle --}}
<script>
    function toggleFilters() {
        const sidebar = document.getElementById('filterSidebar');
        sidebar.classList.toggle('hidden');
    }
</script>
@endsection
