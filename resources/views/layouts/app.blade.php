<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Monitoring Isian PN Bogor') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <span class="text-xl font-bold text-blue-600">
                            <i class="fas fa-gavel mr-2"></i>PN Bogor
                        </span>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden sm:ml-10 sm:flex sm:space-x-8">
                        <!-- Home - All Roles -->
                        <a href="{{ route('dashboard') }}" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium">
                            <i class="fas fa-home mr-2"></i>Home
                        </a>

                        <!-- Daftar Isian - Admin & Pimpinan -->
                        @if(auth()->user()->isAdmin() || auth()->user()->isPimpinan())
                        <a href="{{ route('isian.daftar') }}" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('isian.daftar') || request()->routeIs('isian.create') || request()->routeIs('isian.edit') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium">
                            <i class="fas fa-list-alt mr-2"></i>Daftar Isian
                        </a>
                        @endif

                        <!-- List Isian - All Roles -->
                        <a href="{{ route('isian.list') }}" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('isian.list') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium">
                            <i class="fas fa-eye mr-2"></i>List Isian
                        </a>

                        <!-- Verifikasi Isian - Verifikator Only -->
                        @if(auth()->user()->isVerifikator())
                        <a href="{{ route('isian.verifikasi') }}" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('isian.verifikasi') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} text-sm font-medium">
                            <i class="fas fa-check-circle mr-2"></i>Verifikasi Isian
                        </a>
                        @endif
                    </div>
                </div>

                <!-- User Dropdown -->
                <div class="flex items-center">
                    <div class="relative">
                        <button onclick="toggleDropdown()" class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900">
                            <i class="fas fa-user-circle text-2xl mr-2"></i>
                            <span>{{ auth()->user()->name }}</span>
                            <span class="ml-2 px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                {{ auth()->user()->role->display_name }}
                            </span>
                            <i class="fas fa-chevron-down ml-2"></i>
                        </button>
                        
                        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- Page Content -->
    <main class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const button = event.target.closest('button');
            if (!button && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>