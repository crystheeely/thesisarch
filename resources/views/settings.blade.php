@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white shadow rounded-xl mt-10">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Account Settings</h2>

    {{-- Update Email --}}
    <form action="{{ route('settings.updateEmail') }}" method="POST" class="mb-8">
        @csrf
        @method('PATCH')
        <h3 class="text-lg font-semibold mb-2">Update Email</h3>
        <div class="flex flex-col sm:flex-row gap-4 items-center">
            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                class="w-full sm:w-2/3 px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update Email</button>
        </div>
    </form>

    {{-- Update Password --}}
    <form action="{{ route('settings.updatePassword') }}" method="POST" class="mb-8">
        @csrf
        @method('PATCH')
        <h3 class="text-lg font-semibold mb-2">Update Password</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <input type="password" name="current_password" placeholder="Current Password" required
                class="px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            <input type="password" name="new_password" placeholder="New Password" required
                class="px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            <input type="password" name="new_password_confirmation" placeholder="Confirm New Password" required
                class="px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <button type="submit"
            class="mt-4 bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Update Password</button>
    </form>

    {{-- Delete Account --}}
    <form action="{{ route('settings.deleteAccount') }}" method="POST"
        onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone.');">
        @csrf
        @method('DELETE')
        <h3 class="text-lg font-semibold mb-2 text-red-600">Danger Zone</h3>
        <p class="text-sm text-gray-600 mb-4">Deleting your account is permanent and cannot be undone.</p>
        <button type="submit"
            class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700">Delete Account</button>
    </form>
</div>
@endsection
