<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Monitoring Isian</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-red-600 via-red-700 to-red-900 relative">
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-0 w-96 h-96 bg-white opacity-5 rounded-full -ml-48 -mt-48 animate-pulse"></div>
        <div class="absolute top-1/4 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-0 left-1/4 w-80 h-80 bg-white opacity-5 rounded-full -mb-40 animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 right-1/3 w-48 h-48 bg-white opacity-5 rounded-full animate-pulse" style="animation-delay: 1.5s;"></div>
    </div>

    <!-- Pattern Overlay -->
    <div class="fixed inset-0 bg-pattern opacity-10 pointer-events-none"></div>

    <div class="h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-md w-full space-y-4">
            <!-- Header Section -->
            <div class="text-center mb-4">
                <div class="mx-auto h-16 w-16 bg-white rounded-2xl flex items-center justify-center shadow-2xl transform hover:scale-105 transition-transform duration-300 border-4 border-red-200">
                    <i class="fas fa-balance-scale text-2xl text-red-600"></i>
                </div>
                <h2 class="mt-4 text-3xl font-extrabold text-white drop-shadow-lg">
                    Selamat Datang
                </h2>
                <p class="mt-1 text-sm text-red-100 font-medium">
                    Sistem Monitoring Isian
                </p>
                <p class="text-xs text-red-200 mt-1 bg-white bg-opacity-10 inline-block px-3 py-1 rounded-full">
                    Pengadilan Negeri Bogor
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Login Card -->
            <div class="bg-white rounded-3xl shadow-2xl p-8 space-y-5 border-2 border-red-100">
                <div class="text-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Masuk ke Akun Anda</h3>
                    <p class="text-sm text-gray-500 mt-1">Silakan login untuk melanjutkan</p>
                </div>
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-semibold text-sm" />
                        <div class="mt-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400 text-sm"></i>
                            </div>
                            <x-text-input 
                                id="email" 
                                class="block w-full pl-10 pr-3 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200" 
                                type="email" 
                                name="email" 
                                :value="old('email')" 
                                required 
                                autofocus 
                                autocomplete="username"
                                placeholder="nama@email.com" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-semibold text-sm" />
                        <div class="mt-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400 text-sm"></i>
                            </div>
                            <x-text-input 
                                id="password" 
                                class="block w-full pl-10 pr-3 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                                type="password"
                                name="password"
                                required 
                                autocomplete="current-password"
                                placeholder="••••••••" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                            <input 
                                id="remember_me" 
                                type="checkbox" 
                                class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500 focus:ring-2 w-4 h-4 cursor-pointer transition-all" 
                                name="remember">
                            <span class="ml-2 text-sm text-gray-600 group-hover:text-gray-900 transition-colors">
                                {{ __('Ingat saya') }}
                            </span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-red-600 hover:text-red-800 font-medium transition-colors" href="{{ route('password.request') }}">
                                {{ __('Lupa password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-semibold text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 transform hover:scale-105">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            {{ __('Masuk') }}
                        </button>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center pt-3 border-t border-gray-200">
                        <p class="text-xs text-gray-600">
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="font-semibold text-red-600 hover:text-red-800 transition-colors">
                                Daftar sekarang
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .bg-pattern {
            background-image: 
                repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(255,255,255,.03) 35px, rgba(255,255,255,.03) 70px);
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 0.05;
                transform: scale(1);
            }
            50% {
                opacity: 0.1;
                transform: scale(1.05);
            }
        }
    </style>
</body>
</html>