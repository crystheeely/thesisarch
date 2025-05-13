@extends('layouts.app')

@section('content')
<a href="{{ route('profile.edit') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-4">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
        xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
    </svg>
    Back to Profile
</a>

<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-semibold text-gray-700 mb-6">Edit Personal Information</h2>

    @if(session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif

    <form action="{{ route('profile.updatePersonal') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-sm text-gray-700">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm text-gray-700">School</label>
            <input type="text" name="school" value="{{ old('school', $user->school) }}" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block text-sm text-gray-700">Course</label>
            <input type="text" name="course" value="{{ old('course', $user->course) }}" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
    <label for="year" class="block text-sm font-medium text-gray-700">Year Level</label>
    <select name="year" id="year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
        <option value="" disabled {{ old('year', Auth::user()->year) ? '' : 'selected' }}>Select year</option>
        <option value="1st" {{ old('year', Auth::user()->year) == '1st' ? 'selected' : '' }}>1st</option>
        <option value="2nd" {{ old('year', Auth::user()->year) == '2nd' ? 'selected' : '' }}>2nd</option>
        <option value="3rd" {{ old('year', Auth::user()->year) == '3rd' ? 'selected' : '' }}>3rd</option>
        <option value="4th" {{ old('year', Auth::user()->year) == '4th' ? 'selected' : '' }}>4th</option>
        <option value="4th+" {{ old('year', Auth::user()->year) == '4th+' ? 'selected' : '' }}>4th+</option>
    </select>
</div>


        <div class="mb-4">
            <label class="block text-sm text-gray-700">Birthdate</label>
            <input type="date" name="birthdate" value="{{ old('birthdate', $user->birthdate) }}" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block text-sm text-gray-700">Address</label>
            <input type="text" name="address" value="{{ old('address', $user->address) }}" class="w-full border rounded p-2">
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Changes</button>
        </div>
    </form>
</div>
@endsection
