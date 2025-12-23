@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 space-y-6">

    <!-- Info Panel -->
        <div class="bg-gradient-to-r from-red-700 to-red-800 rounded-xl shadow-2xl p-8 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full -ml-24 -mb-24"></div>
            
            <div class="relative z-10 flex items-center">
                <div class="bg-white bg-opacity-20 rounded-xl p-6 mr-6">
                    <i class="fas fa-info-circle text-5xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}!</h3>
                    <p class="text-lg mb-3">Anda login sebagai <span class="font-bold bg-white bg-opacity-20 px-3 py-1 rounded-lg">{{ auth()->user()->role->display_name }}</span></p>
                    <div class="bg-white bg-opacity-10 rounded-lg p-4 mt-3">
                        <p class="text-red-50">
                            @if(auth()->user()->isUser())
                                <i class="fas fa-check-circle mr-2"></i>Anda dapat membuat isian baru dan mengelola isian yang Anda buat.
                            @elseif(auth()->user()->isVerifikator())
                                <i class="fas fa-check-circle mr-2"></i>Anda dapat memverifikasi isian yang telah memiliki link.
                            @elseif(auth()->user()->isPimpinan())
                                <i class="fas fa-check-circle mr-2"></i>Anda dapat melihat semua data dan statistik isian.
                            @elseif(auth()->user()->isAdmin())
                                <i class="fas fa-check-circle mr-2"></i>Anda memiliki akses penuh ke semua fitur sistem.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-800 to-red-900 rounded-xl shadow-2xl p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -mr-48 -mt-48"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full -ml-32 -mb-32"></div>
            
            <div class="relative z-10">
                <div class="flex items-center space-x-4 mb-3">
                    <div class="bg-white bg-opacity-20 rounded-xl p-4">
                        <i class="fas fa-chart-line text-4xl text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold text-white">Dashboard Monitoring Isian</h1>
                        <p class="text-red-100 text-lg mt-1">Pengadilan Negeri Bogor</p>
                    </div>
                </div>
            </div>
        </div>

        @if(auth()->user()->isAdmin() || auth()->user()->isPimpinan())
        <!-- Statistics Cards - Only for Admin & Pimpinan -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Isian -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-600 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-bold uppercase">Total Daftar Isian</p>
                        <p class="text-4xl font-bold text-gray-900 mt-3">{{ $statistics['total'] }}</p>
                        <p class="text-xs text-gray-500 mt-2">Total keseluruhan</p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-5 shadow-lg">
                        <i class="fas fa-file-alt text-3xl text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Belum Isi Link -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-600 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-bold uppercase">Belum Isi Link</p>
                        <p class="text-4xl font-bold text-gray-900 mt-3">{{ $statistics['belum_isi_link'] }}</p>
                        <p class="text-xs text-gray-500 mt-2">Menunggu input</p>
                    </div>
                    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl p-5 shadow-lg">
                        <i class="fas fa-link text-3xl text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Verifikasi Disetujui -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-600 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-bold uppercase">Verifikasi Disetujui</p>
                        <p class="text-4xl font-bold text-gray-900 mt-3">{{ $statistics['disetujui'] }}</p>
                        <p class="text-xs text-gray-500 mt-2">Sudah terverifikasi</p>
                    </div>
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-5 shadow-lg">
                        <i class="fas fa-check-circle text-3xl text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Verifikasi Ditolak -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-600 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-bold uppercase">Verifikasi Ditolak</p>
                        <p class="text-4xl font-bold text-gray-900 mt-3">{{ $statistics['ditolak'] }}</p>
                        <p class="text-xs text-gray-500 mt-2">Perlu perbaikan</p>
                    </div>
                    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-5 shadow-lg">
                        <i class="fas fa-times-circle text-3xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Actions - Enhanced Version -->
        <div class="bg-white rounded-xl shadow-xl p-8">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center">
                    <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-xl p-3 mr-4 shadow-lg">
                        <i class="fas fa-bolt text-2xl text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Quick Actions</h2>
                        <p class="text-sm text-gray-500 mt-1">Akses cepat ke fitur utama sistem</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @if(auth()->user()->isUser() || auth()->user()->isAdmin())
                <a href="{{ route('isian.create') }}" class="group relative bg-white rounded-2xl p-6 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:-translate-y-2 border-2 border-blue-100 hover:border-blue-300 overflow-hidden">
                    <!-- Background decoration -->
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-300"></div>
                    
                    <div class="relative">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 w-16 h-16 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
                            <i class="fas fa-plus-circle text-2xl text-white"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg mb-2 group-hover:text-blue-600 transition-colors">Tambah Isian Baru</h3>
                        <p class="text-sm text-gray-600">Buat data isian baru dengan mudah</p>
                        
                        <!-- Arrow indicator -->
                        <div class="mt-4 flex items-center text-blue-600 opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-sm font-semibold mr-2">Mulai</span>
                            <i class="fas fa-arrow-right text-sm group-hover:translate-x-2 transition-transform"></i>
                        </div>
                    </div>
                </a>
                @endif

                <a href="{{ route('isian.daftar') }}" class="group relative bg-white rounded-2xl p-6 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:-translate-y-2 border-2 border-green-100 hover:border-green-300 overflow-hidden">
                    <!-- Background decoration -->
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-green-100 to-green-200 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-300"></div>
                    
                    <div class="relative">
                        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-4 w-16 h-16 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
                            <i class="fas fa-list text-2xl text-white"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg mb-2 group-hover:text-green-600 transition-colors">Daftar Isian</h3>
                        <p class="text-sm text-gray-600">Kelola dan edit isian Anda</p>
                        
                        <!-- Arrow indicator -->
                        <div class="mt-4 flex items-center text-green-600 opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-sm font-semibold mr-2">Lihat</span>
                            <i class="fas fa-arrow-right text-sm group-hover:translate-x-2 transition-transform"></i>
                        </div>
                    </div>
                </a>

                @if(auth()->user()->isVerifikator())
                <a href="{{ route('isian.verifikasi') }}" class="group relative bg-white rounded-2xl p-6 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:-translate-y-2 border-2 border-purple-100 hover:border-purple-300 overflow-hidden">
                    <!-- Background decoration -->
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-purple-100 to-purple-200 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-300"></div>
                    
                    <div class="relative">
                        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-4 w-16 h-16 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
                            <i class="fas fa-check-double text-2xl text-white"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg mb-2 group-hover:text-purple-600 transition-colors">Verifikasi Isian</h3>
                        <p class="text-sm text-gray-600">Proses dan validasi data isian</p>
                        
                        <!-- Arrow indicator -->
                        <div class="mt-4 flex items-center text-purple-600 opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-sm font-semibold mr-2">Proses</span>
                            <i class="fas fa-arrow-right text-sm group-hover:translate-x-2 transition-transform"></i>
                        </div>
                    </div>
                </a>
                @endif

                <a href="{{ route('isian.list') }}" class="group relative bg-white rounded-2xl p-6 transition-all duration-300 shadow-lg hover:shadow-2xl transform hover:-translate-y-2 border-2 border-indigo-100 hover:border-indigo-300 overflow-hidden">
                    <!-- Background decoration -->
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-300"></div>
                    
                    <div class="relative">
                        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl p-4 w-16 h-16 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
                            <i class="fas fa-eye text-2xl text-white"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg mb-2 group-hover:text-indigo-600 transition-colors">List Isian</h3>
                        <p class="text-sm text-gray-600">Lihat semua data isian sistem</p>
                        
                        <!-- Arrow indicator -->
                        <div class="mt-4 flex items-center text-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-sm font-semibold mr-2">Buka</span>
                            <i class="fas fa-arrow-right text-sm group-hover:translate-x-2 transition-transform"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection