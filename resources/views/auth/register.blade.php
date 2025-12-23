<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Monitoring Isian</title>
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

    <div class="min-h-screen flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-md w-full space-y-6">
            <!-- Header Section -->
            <div class="text-center mb-6">
                <div class="mx-auto h-20 w-20 bg-white rounded-3xl flex items-center justify-center shadow-2xl transform hover:scale-105 transition-transform duration-300 border-4 border-red-200">
                    <i class="fas fa-user-plus text-3xl text-red-600"></i>
                </div>
                <h2 class="mt-6 text-4xl font-extrabold text-white drop-shadow-lg">
                    Buat Akun Baru
                </h2>
                <p class="mt-2 text-base text-red-100 font-medium">
                    Daftar untuk mengakses sistem
                </p>
                <p class="text-sm text-red-200 mt-2 bg-white bg-opacity-10 inline-block px-4 py-1 rounded-full">
                    Pengadilan Negeri Bogor
                </p>
            </div>

            <!-- Register Card -->
            <div class="bg-white rounded-3xl shadow-2xl p-8 space-y-5 border-2 border-red-100">
                <div class="text-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Registrasi Akun</h3>
                    <p class="text-sm text-gray-500 mt-1">Lengkapi data di bawah ini</p>
                </div>
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Nama Lengkap')" class="text-gray-700 font-semibold" />
                        <div class="mt-2 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <x-text-input 
                                id="name" 
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200" 
                                type="text" 
                                name="name" 
                                :value="old('name')" 
                                required 
                                autofocus 
                                autocomplete="name"
                                placeholder="Masukkan nama lengkap" />
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-semibold" />
                        <div class="mt-2 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <x-text-input 
                                id="email" 
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200" 
                                type="email" 
                                name="email" 
                                :value="old('email')" 
                                required 
                                autocomplete="username"
                                placeholder="nama@email.com" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-semibold" />
                        <div class="mt-2 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <x-text-input 
                                id="password" 
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                                type="password"
                                name="password"
                                required 
                                autocomplete="new-password"
                                placeholder="Minimal 8 karakter" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-gray-700 font-semibold" />
                        <div class="mt-2 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <x-text-input 
                                id="password_confirmation" 
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                                type="password"
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password"
                                placeholder="Ulangi password" />
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Password Requirements -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-3">
                        <p class="text-xs text-blue-800 font-medium mb-2">
                            <i class="fas fa-info-circle mr-1"></i> Persyaratan Password:
                        </p>
                        <ul class="text-xs text-blue-700 space-y-1 ml-5 list-disc">
                            <li>Minimal 8 karakter</li>
                            <li>Kombinasi huruf dan angka direkomendasikan</li>
                        </ul>
                    </div>

                    <!-- Register Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-semibold text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 transform hover:scale-105">
                            <i class="fas fa-user-check mr-2"></i>
                            {{ __('Daftar') }}
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="font-semibold text-red-600 hover:text-red-800 transition-colors">
                                Masuk di sini
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center">
                <p class="text-sm text-red-100 bg-white bg-opacity-10 inline-block px-6 py-2 rounded-full">
                    © 2024 Pengadilan Negeri Bogor
                </p>
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