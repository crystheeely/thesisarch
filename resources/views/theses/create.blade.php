@extends('layouts.app')

@section('content')
<!-- Back Button -->
<div class="mb-6">
    <a href="{{ url()->previous() }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        <span class="font-medium">Back</span>
    </a>
</div>

<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Upload Thesis</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-600 p-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('theses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Title -->
        <div class="mb-4">
            <label class="block text-gray-700">Title:</label>
            <input type="text" name="title" class="w-full border rounded-lg p-2" value="{{ old('title') }}" required>
        </div>

        <!-- Abstract -->
        <div class="mb-4">
            <label class="block text-gray-700">Abstract:</label>
            <textarea name="abstract" class="w-full border rounded-lg p-2 h-40 resize-none" required>{{ old('abstract') }}</textarea>
        </div>

        <!-- Keywords -->
        <div class="mb-4">
            <label class="block font-medium text-gray-700">Keywords (comma-separated)</label>
            <input type="text" name="keywords" class="w-full border rounded-lg p-2" value="{{ old('keywords') }}" placeholder="e.g., AI, machine learning, deep learning">
        </div>

        <!-- Academic Year -->
        <div class="mb-4">
            <label class="block text-gray-700">Academic Year:</label>
            <input type="text" name="academic_year" class="w-full border rounded-lg p-2" placeholder="e.g., 2024-2025" value="{{ old('academic_year') }}" required>
        </div>

        <!-- Month -->
        <div class="mb-4">
            <label class="block text-gray-700">Month:</label>
            <select name="month" class="w-full border rounded-lg p-2" required>
                <option value="">Select Month</option>
                @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                    <option value="{{ $month }}" {{ old('month') == $month ? 'selected' : '' }}>{{ $month }}</option>
                @endforeach
            </select>
        </div>

        <!-- Semester -->
        <div class="mb-4">
            <label class="block text-gray-700">Semester:</label>
            <select name="semester" class="w-full border rounded-lg p-2" required>
                <option value="">Select Semester</option>
                <option value="First Semester" {{ old('semester') == 'First Semester' ? 'selected' : '' }}>First Semester</option>
                <option value="Second Semester" {{ old('semester') == 'Second Semester' ? 'selected' : '' }}>Second Semester</option>
            </select>
        </div>

        <!-- Author -->
        <div class="mb-4">
            <label class="block text-gray-700">Author:</label>
            <input type="text" name="author_name" class="w-full border rounded-lg p-2" placeholder="Enter author's full name" value="{{ auth()->user()->full_name }}" required>
        </div>

        <!-- Co-Authors -->
        <div class="mb-4">
            <label class="block text-gray-700">Co-Author(s) (optional):</label>
            <div id="coauthor-list">
                @if(old('co_authors'))
                    @foreach(old('co_authors') as $coauthor)
                        <div class="flex coauthor-item mb-2">
                            <input type="text" name="co_authors[]" class="w-full border rounded-lg p-2" value="{{ $coauthor }}">
                            <button type="button" class="ml-2 px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 remove-coauthor">❌</button>
                        </div>
                    @endforeach
                @else
                    <div class="flex coauthor-item mb-2">
                        <input type="text" name="co_authors[]" class="w-full border rounded-lg p-2" placeholder="Enter co-author's full name">
                        <button type="button" class="ml-2 px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 remove-coauthor hidden">❌</button>
                    </div>
                @endif
            </div>
            <button type="button" id="add-coauthor" class="bg-green-500 text-white px-3 py-1 rounded-lg hover:bg-green-600 mt-2">
                + Add Co-Author
            </button>
        </div>

        <!-- Adviser -->
        <div class="mb-4">
            <label class="block text-gray-700">Adviser:</label>
            <select name="faculty_id" class="w-full border rounded-lg p-2" required>
                <option value="">Select Adviser</option>
                @foreach ($facultyUsers as $faculty)
                    <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>
                        {{ $faculty->full_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Thesis File -->
        <div class="mb-4">
            <label class="block text-gray-700">Upload File (PDF or DOC):</label>
            <input type="file" name="file" class="w-full border rounded-lg p-2" accept=".pdf,.doc,.docx" required value="{{ old('file') }}">
            <small class="text-gray-500">Accepted formats: .pdf, .doc, .docx | Max size: 10MB</small>
        </div>

        <!-- Requirements -->
        <h3 class="text-lg font-semibold mt-6 mb-2">Thesis Requirements</h3>
        <div id="requirement-files-wrapper" class="space-y-4">
            @if(old('requirement_titles'))
                @foreach(old('requirement_titles') as $i => $title)
                    <div class="requirement-file-row flex items-center gap-2 mb-2">
                        <input type="text" name="requirement_titles[]" class="w-1/2 border rounded-lg p-2" value="{{ $title }}" placeholder="Requirement Title (e.g. User Manual)" />
                        <input type="file" name="requirement_files[]" class="w-1/2 border rounded-lg p-2" />
                        <button type="button" class="remove-btn text-red-600 font-bold">✖</button>
                    </div>
                @endforeach
            @else
                <div class="requirement-file-row flex items-center gap-2 mb-2">
                    <input type="text" name="requirement_titles[]" class="w-1/2 border rounded-lg p-2" placeholder="Requirement Title (e.g. User Manual)" />
                    <input type="file" name="requirement_files[]" class="w-1/2 border rounded-lg p-2" />
                    <button type="button" class="remove-btn text-red-600 font-bold">✖</button>
                </div>
            @endif
        </div>
        <button type="button" id="add-requirement" class="mt-2 bg-green-500 text-white px-4 py-1 rounded hover:bg-green-600">
            + Add Requirement File
        </button>
        <br/>
        <!-- Submit -->
        <button type="submit" class="mt-6 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
            Submit
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Add Co-Author
    document.getElementById('add-coauthor').addEventListener('click', function() {
        let coauthorList = document.getElementById('coauthor-list');
        let coauthorDiv = document.createElement('div');
        coauthorDiv.className = 'flex coauthor-item mb-2';

        let inputField = document.createElement('input');
        inputField.type = 'text';
        inputField.name = 'co_authors[]';
        inputField.className = 'w-full border rounded-lg p-2';
        inputField.placeholder = "Enter co-author's full name";

        let deleteButton = document.createElement('button');
        deleteButton.type = 'button';
        deleteButton.className = 'ml-2 px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 remove-coauthor';
        deleteButton.innerHTML = '❌';
        deleteButton.onclick = function () {
            coauthorDiv.remove();
        };

        coauthorDiv.appendChild(inputField);
        coauthorDiv.appendChild(deleteButton);
        coauthorList.appendChild(coauthorDiv);
    });

    // Remove Co-Author
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-coauthor')) {
            e.target.closest('.coauthor-item').remove();
        }
    });

    // Add Requirement
    document.getElementById('add-requirement').addEventListener('click', function () {
        const wrapper = document.getElementById('requirement-files-wrapper');
        const newRow = document.createElement('div');
        newRow.classList.add('requirement-file-row', 'flex', 'items-center', 'gap-2', 'mb-2');
        newRow.innerHTML = `
            <input type="text" name="requirement_titles[]" class="w-1/2 border rounded-lg p-2" placeholder="Requirement Title" />
            <input type="file" name="requirement_files[]" class="w-1/2 border rounded-lg p-2" />
            <button type="button" class="remove-btn text-red-600 font-bold">✖</button>
        `;
        wrapper.appendChild(newRow);
    });

    // Remove Requirement
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-btn')) {
            e.target.closest('.requirement-file-row').remove();
        }
    });
</script>
@endpush
