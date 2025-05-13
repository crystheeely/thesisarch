@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-center mb-4">Register</h2>

    @if(session('success'))
        <p class="text-green-600 text-center mb-4">{{ session('success') }}</p>
    @endif

    @if($errors->any())
        <p class="text-red-600 text-center mb-4">{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Full Name -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Full Name:</label>
            <input type="text" name="full_name" class="w-full p-2 border rounded-lg" required>
        </div>

        <!-- Role Selection -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Register as:</label>
            <select name="role" id="role" class="w-full p-2 border rounded-lg" required>
                <option value="student">Student</option>
                <option value="faculty">Faculty</option>
            </select>
        </div>

        <!-- ID Number -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">ID Number:</label>
            <input type="text" name="id_number" id="id_number" 
                class="w-full p-2 border rounded-lg" 
                placeholder="Enter ID Number" 
                required 
                onchange="validateID(this)">
            <p id="idError" class="text-red-500 text-sm mt-1"></p>
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Email:</label>
            <input type="email" name="email" class="w-full p-2 border rounded-lg" required>
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Password:</label>
            <input type="password" name="password" class="w-full p-2 border rounded-lg" required>
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Confirm Password:</label>
            <input type="password" name="password_confirmation" class="w-full p-2 border rounded-lg" required>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
            Register
        </button>
    </form>

    <!-- Login Link -->
    <p class="mt-4 text-center">
        Already have an account? 
        <a href="{{ route('login') }}" class="text-blue-600 font-semibold">Login here</a>.
    </p>
</div>

<!-- JavaScript for ID Validation -->
<script>
function validateID(input) {
    let value = input.value;
    let idError = document.getElementById('idError');
    let role = document.querySelector('select[name="role"]').value;

    if (role === 'faculty') {
        let facultyRegex = /^\d{4}-\d{3}$/; // Admin format YYYY-NNN
        idError.textContent = facultyRegex.test(value) ? "" : "Invalid Admin ID (format: YYYY-NNN)";
    } else {
        let studentRegex = /^\d{4}-\d{4}$/; // Student format YYYY-NNNN
        idError.textContent = studentRegex.test(value) ? "" : "Invalid Student ID (format: YYYY-NNNN)";
    }
}

// Update validation when role is changed
document.getElementById('role').addEventListener("change", function() {
    validateID(document.getElementById("id_number"));
});
</script>
@endsection