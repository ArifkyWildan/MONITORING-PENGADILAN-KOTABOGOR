<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Monitoring Isian PN Bogor') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-slide-down {
            animation: slideDown 0.3s ease-out;
        }
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #7F1D1D, #991B1B);
            transition: width 0.3s ease;
        }
        .nav-link:hover::after {
            width: 100%;
        }
        .nav-link.active::after {
            width: 100%;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-red-50 to-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-gradient-to-r from-red-800 to-red-900 shadow-2xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center mr-8">
                        <div class="bg-white bg-opacity-20 rounded-xl p-3 mr-3 backdrop-blur-sm">
    <img 
        src="/images/logo_ma.png" 
        alt="Gavel Icon"
        class="w-8 h-8 object-contain"
    >
</div>

                        <div>
                            <span class="text-2xl font-bold text-white block">PN Bogor</span>
                            <span class="text-xs text-red-100">Monitoring Isian</span>
                        </div>
                    </div>

                    <!-- Navigation Links - Desktop -->
                    <div class="hidden lg:flex lg:space-x-2">
                        <!-- Home - All Roles -->
                        <a href="{{ route('dashboard') }}" 
                           class="nav-link {{ request()->routeIs('dashboard') ? 'active bg-white bg-opacity-20' : '' }} inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold text-white hover:bg-white hover:bg-opacity-20 transition-all duration-200">
                            <i class="fas fa-home mr-2"></i>Home
                        </a>

                        <!-- Daftar Isian -->
                        <a href="{{ route('isian.daftar') }}" 
                           class="nav-link {{ request()->routeIs('isian.daftar') || request()->routeIs('isian.create') || request()->routeIs('isian.edit') ? 'active bg-white bg-opacity-20' : '' }} inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold text-white hover:bg-white hover:bg-opacity-20 transition-all duration-200">
                            <i class="fas fa-list-alt mr-2"></i>Daftar Isian
                        </a>

                        <!-- List Isian -->
                        <a href="{{ route('isian.list') }}" 
                           class="nav-link {{ request()->routeIs('isian.list') ? 'active bg-white bg-opacity-20' : '' }} inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold text-white hover:bg-white hover:bg-opacity-20 transition-all duration-200">
                            <i class="fas fa-eye mr-2"></i>List Isian
                        </a>

                        <!-- Verifikasi Isian -->
                        @if(auth()->user()->isVerifikator() || auth()->user()->isAdmin())
                        <a href="{{ route('isian.verifikasi') }}" 
                           class="nav-link {{ request()->routeIs('isian.verifikasi') ? 'active bg-white bg-opacity-20' : '' }} inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold text-white hover:bg-white hover:bg-opacity-20 transition-all duration-200">
                            <i class="fas fa-check-circle mr-2"></i>Verifikasi
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Right Side -->
                <div class="flex items-center space-x-4">
                    <!-- User Dropdown - Desktop -->
                    <div class="hidden md:block relative">
                        <button onclick="toggleDropdown()" class="flex items-center space-x-3 bg-white bg-opacity-10 hover:bg-opacity-20 px-4 py-2 rounded-xl text-white font-semibold transition-all duration-200 backdrop-blur-sm">
                            <div class="h-8 w-8 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div class="text-left">
                                <div class="text-sm font-bold">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-red-100">{{ auth()->user()->role->display_name }}</div>
                            </div>
                            <i class="fas fa-chevron-down text-sm"></i>
                        </button>
                        
                        <div id="userDropdown" class="hidden absolute right-0 mt-3 w-64 bg-white rounded-xl shadow-2xl py-2 z-50 animate-slide-down border border-gray-200">
                            <div class="px-4 py-3 border-b border-gray-200">
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ auth()->user()->email }}</p>
                                <span class="inline-block mt-2 px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">
                                    {{ auth()->user()->role->display_name }}
                                </span>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-3 text-sm font-semibold text-red-700 hover:bg-red-50 transition-colors duration-150 flex items-center">
                                    <i class="fas fa-sign-out-alt mr-3 text-red-600"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button onclick="toggleMobileMenu()" class="lg:hidden text-white p-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-all duration-200">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div id="mobileMenu" class="hidden lg:hidden bg-red-900 bg-opacity-95 backdrop-blur-sm border-t border-red-700">
            <div class="px-4 py-4 space-y-2">
                <a href="{{ route('dashboard') }}" 
                   class="block px-4 py-3 rounded-lg text-white font-semibold {{ request()->routeIs('dashboard') ? 'bg-white bg-opacity-20' : 'hover:bg-white hover:bg-opacity-10' }} transition-all duration-200">
                    <i class="fas fa-home mr-3"></i>Home
                </a>
                <a href="{{ route('isian.daftar') }}" 
                   class="block px-4 py-3 rounded-lg text-white font-semibold {{ request()->routeIs('isian.daftar') || request()->routeIs('isian.create') || request()->routeIs('isian.edit') ? 'bg-white bg-opacity-20' : 'hover:bg-white hover:bg-opacity-10' }} transition-all duration-200">
                    <i class="fas fa-list-alt mr-3"></i>Daftar Isian
                </a>
                <a href="{{ route('isian.list') }}" 
                   class="block px-4 py-3 rounded-lg text-white font-semibold {{ request()->routeIs('isian.list') ? 'bg-white bg-opacity-20' : 'hover:bg-white hover:bg-opacity-10' }} transition-all duration-200">
                    <i class="fas fa-eye mr-3"></i>List Isian
                </a>
                @if(auth()->user()->isVerifikator() || auth()->user()->isAdmin())
                <a href="{{ route('isian.verifikasi') }}" 
                   class="block px-4 py-3 rounded-lg text-white font-semibold {{ request()->routeIs('isian.verifikasi') ? 'bg-white bg-opacity-20' : 'hover:bg-white hover:bg-opacity-10' }} transition-all duration-200">
                    <i class="fas fa-check-circle mr-3"></i>Verifikasi Isian
                </a>
                @endif
                
                <!-- User Info in Mobile -->
                <div class="border-t border-red-700 pt-4 mt-4">
                    <div class="px-4 py-2 text-white">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="h-10 w-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <div class="font-bold">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-red-100">{{ auth()->user()->email }}</div>
                            </div>
                        </div>
                        <span class="inline-block px-3 py-1 text-xs font-bold rounded-full bg-white bg-opacity-20">
                            {{ auth()->user()->role->display_name }}
                        </span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="w-full px-4 py-3 rounded-lg text-white font-semibold bg-red-700 hover:bg-red-800 transition-all duration-200 flex items-center justify-center">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-xl shadow-lg animate-slide-down flex items-center" role="alert">
            <div class="bg-white bg-opacity-20 rounded-lg p-3 mr-4">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
            <div>
                <p class="font-bold text-lg">Berhasil!</p>
                <p class="text-green-50">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-4 rounded-xl shadow-lg animate-slide-down flex items-center" role="alert">
            <div class="bg-white bg-opacity-20 rounded-lg p-3 mr-4">
                <i class="fas fa-exclamation-circle text-2xl"></i>
            </div>
            <div>
                <p class="font-bold text-lg">Error!</p>
                <p class="text-red-50">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Page Content -->
    <main class="pb-12">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-red-800 to-red-900 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="bg-white bg-opacity-20 rounded-lg p-2">
                            <i class="fas fa-gavel text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-lg">Pengadilan Negeri Bogor</p>
                            <p class="text-xs text-red-100">Monitoring Isian</p>
                        </div>
                    </div>
                </div>
                <div class="text-center md:text-right">
                    <p class="text-red-100 text-sm">© {{ date('Y') }} Pengadilan Negeri Bogor</p>
                    <p class="text-red-200 text-xs mt-1">Mahkamah Agung Republik Indonesia</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('hidden');
        }

        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const button = event.target.closest('button[onclick="toggleDropdown()"]');
            if (!button && dropdown && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Auto hide flash messages after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>