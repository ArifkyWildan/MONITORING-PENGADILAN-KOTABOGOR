@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 to-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header dengan Gradient Merah Maroon -->
        <div class="bg-gradient-to-r from-red-800 to-red-900 rounded-xl shadow-2xl p-8 mb-6 relative overflow-hidden">
            <!-- Decorative Pattern -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full -ml-24 -mb-24"></div>
            
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="bg-white bg-opacity-20 rounded-lg p-3">
                            <i class="fas fa-plus-circle text-3xl text-white"></i>
                        </div>
                        <h1 class="text-3xl font-bold text-white">
                            Tambah Isian Baru
                        </h1>
                    </div>
                    <p class="text-red-100 text-lg ml-1">Buat data isian baru untuk bagian tertentu</p>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white bg-opacity-10 rounded-full p-4">
                        <i class="fas fa-file-alt text-5xl text-white opacity-80"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-red-700 to-red-800 px-8 py-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-edit mr-2"></i>
                    Form Input Data Isian
                </h2>
            </div>

            <!-- Form Body -->
            <form action="{{ route('isian.store') }}" method="POST" class="p-8">
                @csrf
                <div class="space-y-8">
                    <!-- Nama Bagian -->
                    <div class="group">
                        <label class="block text-sm font-bold text-gray-800 mb-3">
                            <div class="flex items-center space-x-2">
                                <div class="bg-red-100 rounded-lg p-2">
                                    <i class="fas fa-building text-red-700"></i>
                                </div>
                                <span>Nama Bagian</span>
                                <span class="text-red-600">*</span>
                            </div>
                        </label>
                        <select name="bagian_id" required class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl shadow-sm focus:border-red-600 focus:ring-4 focus:ring-red-100 transition-all duration-200 @error('bagian_id') border-red-500 @enderror">
                            <option value="">-- Pilih Bagian --</option>
                            @foreach($bagians as $bagian)
                            <option value="{{ $bagian->id }}" {{ old('bagian_id') == $bagian->id ? 'selected' : '' }}>
                                {{ $bagian->nama_bagian }}
                            </option>
                            @endforeach
                        </select>
                        @error('bagian_id')
                        <div class="mt-2 flex items-center text-red-600 text-sm">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span>{{ $message }}</span>
                        </div>
                        @enderror
                    </div>

                    <!-- Daftar Isi -->
                    <div class="group">
                        <label class="block text-sm font-bold text-gray-800 mb-3">
                            <div class="flex items-center space-x-2">
                                <div class="bg-red-100 rounded-lg p-2">
                                    <i class="fas fa-align-left text-red-700"></i>
                                </div>
                                <span>Daftar Isi</span>
                                <span class="text-red-600">*</span>
                            </div>
                        </label>
                        <textarea name="daftar_isi" rows="10" required placeholder="Masukkan daftar isi secara detail..." class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl shadow-sm focus:border-red-600 focus:ring-4 focus:ring-red-100 transition-all duration-200 resize-none @error('daftar_isi') border-red-500 @enderror">{{ old('daftar_isi') }}</textarea>
                        @error('daftar_isi')
                        <div class="mt-2 flex items-center text-red-600 text-sm">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span>{{ $message }}</span>
                        </div>
                        @enderror
                        <p class="mt-3 text-sm text-gray-600 flex items-start">
                            <i class="fas fa-info-circle text-red-600 mr-2 mt-0.5"></i>
                            <span>Jelaskan isi/konten dari bagian ini secara detail dan lengkap</span>
                        </p>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-500 rounded-lg p-5">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-lightbulb text-2xl text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-bold text-blue-900 mb-1">Tips Pengisian</h3>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-blue-600 mr-2 mt-0.5"></i>
                                        <span>Pastikan memilih bagian yang sesuai</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-blue-600 mr-2 mt-0.5"></i>
                                        <span>Isi daftar isi dengan lengkap dan jelas</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-blue-600 mr-2 mt-0.5"></i>
                                        <span>Periksa kembali sebelum menyimpan</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t-2 border-gray-200">
                        <button type="submit" class="flex-1 group relative bg-gradient-to-r from-red-700 to-red-800 hover:from-red-800 hover:to-red-900 text-white px-8 py-4 rounded-xl font-bold text-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <span class="flex items-center justify-center">
                                <i class="fas fa-save mr-3 text-xl"></i>
                                Simpan Data
                            </span>
                        </button>
                        <a href="{{ route('isian.daftar') }}" class="flex-1 text-center border-3 border-gray-400 hover:border-gray-500 bg-white hover:bg-gray-50 text-gray-700 px-8 py-4 rounded-xl font-bold text-lg transition-all duration-200 shadow-md hover:shadow-lg">
                            <span class="flex items-center justify-center">
                                <i class="fas fa-times mr-3 text-xl"></i>
                                Batal
                            </span>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Back to List -->
        <div class="mt-6 text-center">
            <a href="{{ route('isian.daftar') }}" class="inline-flex items-center text-red-700 hover:text-red-900 font-semibold transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Daftar Isian
            </a>
        </div>
    </div>
</div>
@endsection