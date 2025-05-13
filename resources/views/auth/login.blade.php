@extends('layouts.app')

@section('content')
<div class="container max-w-md mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <h2 class="text-2xl font-bold text-center">Login</h2>

    @if(session('success'))
        <p class="text-green-600 text-center">{{ session('success') }}</p>
    @endif

    @if($errors->any())
        <p class="text-red-600 text-center">{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700">Email:</label>
            <input type="email" name="email" class="w-full p-2 border rounded-lg" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Password:</label>
            <input type="password" name="password" class="w-full p-2 border rounded-lg" required>
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg">Login</button>
    </form>

    <p class="mt-4 text-center">Don't have an account? 
        <a href="{{ route('register') }}" class="text-blue-600">Register here</a>.
    </p>
</div>
@endsection