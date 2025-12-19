{{-- create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900">
            <i class="fas fa-plus-circle mr-2 text-blue-600"></i>Tambah Isian Baru
        </h1>
        <p class="text-gray-600 mt-1">Buat data isian baru untuk bagian tertentu</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('isian.store') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <!-- Nama Bagian -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-building mr-1"></i>Nama Bagian <span class="text-red-500">*</span>
                    </label>
                    <select name="bagian_id" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('bagian_id') border-red-500 @enderror">
                        <option value="">-- Pilih Bagian --</option>
                        @foreach($bagians as $bagian)
                        <option value="{{ $bagian->id }}" {{ old('bagian_id') == $bagian->id ? 'selected' : '' }}>
                            {{ $bagian->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('bagian_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Daftar Isi -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-1"></i>Daftar Isi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="daftar_isi" rows="8" required placeholder="Masukkan daftar isi..." class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 @error('daftar_isi') border-red-500 @enderror">{{ old('daftar_isi') }}</textarea>
                    @error('daftar_isi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Jelaskan isi/konten dari bagian ini secara detail</p>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-4 border-t">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                    <a href="{{ route('isian.daftar') }}" class="flex-1 text-center border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection