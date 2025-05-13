<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Thesis Archives</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-50 font-sans">

    <!-- Navbar -->
    <nav class="bg-blue-600 shadow-md p-4">
        <div class="container mx-auto flex justify-between items-center">
            <!-- Left: Logo -->
            <div class="text-xl font-bold text-white">
                Thesis Archives
            </div>

            <!-- Center: Navigation Links -->
<div class="space-x-6">
    @auth
    <a href="
        @if(auth()->user()->role === 'admin') 
            {{ route('admin.dashboard') }}
        @elseif(auth()->user()->role === 'faculty') 
            {{ route('faculty.dashboard') }}
        @elseif(auth()->user()->role === 'student') 
            {{ route('student.dashboard') }}
        @endif
    " class="text-white hover:text-blue-200">
        Dashboard
    </a>


    @endauth
    <a href="{{ route('theses.index') }}" class="text-white hover:text-blue-200">Theses</a>
    <a href="{{ route('reports.index') }}" class="text-white hover:text-blue-200">Reports</a>
    <a href="{{ route('about-us') }}" class="text-white hover:text-blue-200">About Us</a>
</div>

            <!-- Right: Profile Dropdown -->
            <div class="flex items-center space-x-4">
                <input type="text" placeholder="Search..." class="px-3 py-1 text-sm rounded border border-white bg-blue-100 text-blue-800 placeholder-blue-300">

                @auth
                    <div class="relative">
                        <!-- Dropdown Toggle Button -->
                        <button id="profileDropdownBtn" class="text-white hover:bg-blue-700 px-4 py-2 rounded-lg">
                            {{ Auth::user()->name }} <span>&#9662;</span>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="profileDropdown" class="absolute right-0 w-48 bg-white shadow-lg rounded-lg mt-2 z-10 hidden">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Profile</a>
                            <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Settings</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-200">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-white hover:underline">Login</a>
                    <a href="{{ route('register') }}" class="text-sm bg-white text-blue-600 px-3 py-1 rounded hover:bg-blue-100">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto py-8">
        @yield('content')
    </main>

    <!-- JavaScript for Dropdown -->
    <script>
        const btn = document.getElementById('profileDropdownBtn');
        const menu = document.getElementById('profileDropdown');

        document.addEventListener('click', function (event) {
            const isClickInside = btn.contains(event.target) || menu.contains(event.target);
            if (!isClickInside) {
                menu.classList.add('hidden');
            }
        });

        btn.addEventListener('click', function () {
            menu.classList.toggle('hidden');
        });
    </script>

    <!-- Add this line to include scripts -->
    @stack('scripts')

</body>

</html>
