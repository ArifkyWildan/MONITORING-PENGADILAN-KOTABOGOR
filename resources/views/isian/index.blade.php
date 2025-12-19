@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-chart-line mr-3 text-blue-600"></i>Dashboard Monitoring Isian
        </h1>
        <p class="mt-2 text-gray-600">Pengadilan Negeri Bogor</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Isian -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Daftar Isian</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['total'] }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-4">
                    <i class="fas fa-file-alt text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Belum Isi Link -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Belum Isi Link</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['belum_isi_link'] }}</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-4">
                    <i class="fas fa-link text-2xl text-yellow-600"></i>
                </div>
            </div>
        </div>

        <!-- Verifikasi Disetujui -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Verifikasi Disetujui</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['disetujui'] }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-4">
                    <i class="fas fa-check-circle text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Verifikasi Ditolak -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Verifikasi Ditolak</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['ditolak'] }}</p>
                </div>
                <div class="bg-red-100 rounded-full p-4">
                    <i class="fas fa-times-circle text-2xl text-red-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">
            <i class="fas fa-bolt mr-2 text-blue-600"></i>Quick Actions
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @if(auth()->user()->isAdmin())
            <a href="{{ route('isian.create') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                <i class="fas fa-plus-circle text-2xl text-blue-600 mr-4"></i>
                <div>
                    <p class="font-semibold text-gray-900">Tambah Isian Baru</p>
                    <p class="text-sm text-gray-600">Buat data isian baru</p>
                </div>
            </a>
            @endif

            <a href="{{ route('isian.list') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
                <i class="fas fa-list text-2xl text-green-600 mr-4"></i>
                <div>
                    <p class="font-semibold text-gray-900">Lihat List Isian</p>
                    <p class="text-sm text-gray-600">Tampilkan semua data</p>
                </div>
            </a>

            @if(auth()->user()->isVerifikator())
            <a href="{{ route('isian.verifikasi') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                <i class="fas fa-check-double text-2xl text-purple-600 mr-4"></i>
                <div>
                    <p class="font-semibold text-gray-900">Verifikasi Isian</p>
                    <p class="text-sm text-gray-600">Proses verifikasi data</p>
                </div>
            </a>
            @endif
        </div>
    </div>

    <!-- Info Panel -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-4xl mr-4"></i>
            <div>
                <h3 class="text-xl font-semibold">Selamat Datang, {{ auth()->user()->name }}!</h3>
                <p class="mt-1">Anda login sebagai <strong>{{ auth()->user()->role->display_name }}</strong></p>
                <p class="text-sm mt-2 opacity-90">Gunakan menu navigasi di atas untuk mengakses fitur sesuai hak akses Anda.</p>
            </div>
        </div>
    </div>
</div>
@endsection