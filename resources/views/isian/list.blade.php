@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 space-y-6">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-800 to-red-900 rounded-xl shadow-2xl p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full -ml-24 -mb-24"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="bg-white bg-opacity-20 rounded-lg p-3">
                            <i class="fas fa-list text-3xl text-white"></i>
                        </div>
                        <h1 class="text-3xl font-bold text-white">List Isian</h1>
                    </div>
                    <p class="text-red-100 text-lg ml-1">Daftar semua isian yang tersedia</p>
                </div>
                <a href="{{ route('dashboard') }}" class="bg-white hover:bg-red-50 text-red-800 px-6 py-3 rounded-xl font-bold transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-4">
                <div class="bg-red-100 rounded-lg p-2 mr-3">
                    <i class="fas fa-filter text-red-700"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Filter Data</h2>
            </div>
            <form method="GET" action="{{ route('isian.list') }}" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Filter Bagian</label>
                    <select name="bagian_id" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl shadow-sm focus:border-red-600 focus:ring-4 focus:ring-red-100 transition-all duration-200">
                        <option value="">Semua Bagian</option>
                        @foreach($bagians as $bagian)
                        <option value="{{ $bagian->id }}" {{ request('bagian_id') == $bagian->id ? 'selected' : '' }}>
                            {{ $bagian->nama_bagian }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-red-700 to-red-800 hover:from-red-800 hover:to-red-900 text-white px-8 py-3 rounded-xl font-bold transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-filter mr-2"></i>Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-red-700 to-red-800">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">No</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Bagian</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Daftar Isi</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Link</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
                            @if(auth()->user()->isUser())
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($isians as $index => $isian)
                        <tr class="hover:bg-red-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                {{ $isians->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-4 py-2 text-sm font-bold bg-red-100 text-red-800 rounded-full">
                                    {{ $isian->bagian->nama_bagian }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ Str::limit($isian->daftar_isi, 50) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($isian->link)
                                    <a href="{{ $isian->link->url_link }}" target="_blank" class="text-red-700 hover:text-red-900 font-semibold hover:underline">
                                        <i class="fas fa-external-link-alt mr-1"></i>{{ $isian->link->text_hyperlink }}
                                    </a>
                                @else
                                    <span class="text-gray-400 italic">Belum ada link</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($isian->verifikasi)
                                    @if($isian->verifikasi->status === 'disetujui')
                                        <span class="px-4 py-2 text-sm font-bold bg-green-100 text-green-800 rounded-full">
                                            <i class="fas fa-check-circle mr-1"></i>Disetujui
                                        </span>
                                    @else
                                        <span class="px-4 py-2 text-sm font-bold bg-red-100 text-red-800 rounded-full">
                                            <i class="fas fa-times-circle mr-1"></i>Ditolak
                                        </span>
                                    @endif
                                @elseif($isian->link)
                                    <span class="px-4 py-2 text-sm font-bold bg-yellow-100 text-yellow-800 rounded-full">
                                        <i class="fas fa-clock mr-1"></i>Menunggu Verifikasi
                                    </span>
                                @else
                                    <span class="px-4 py-2 text-sm font-bold bg-gray-100 text-gray-800 rounded-full">
                                        <i class="fas fa-minus-circle mr-1"></i>Belum Isi Link
                                    </span>
                                @endif
                            </td>
                            @if(auth()->user()->isUser())
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if(!$isian->link)
                                    <button onclick="openLinkModal({{ $isian->id }})" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-2 rounded-lg text-sm font-bold transition-all duration-200 shadow-md hover:shadow-lg">
                                        <i class="fas fa-plus mr-1"></i>Isi Link
                                    </button>
                                @else
                                    <span class="text-gray-400 text-sm italic">Link sudah diisi</span>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-5xl mb-3 text-gray-300"></i>
                                <p class="text-lg font-semibold">Tidak ada data isian</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="bg-white rounded-xl shadow-lg p-4">
            {{ $isians->links() }}
        </div>
    </div>
</div>

<!-- Modal Isi Link (Untuk User) -->
@if(auth()->user()->isUser())
<div id="linkModal" class="fixed inset-0 bg-black bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-8 border w-full max-w-md shadow-2xl rounded-xl bg-white">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                <div class="bg-red-100 rounded-lg p-2 mr-3">
                    <i class="fas fa-link text-red-700"></i>
                </div>
                Tambah Link
            </h3>
            <button onclick="closeLinkModal()" class="text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-lg transition-all duration-150">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="linkForm" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-text-width mr-1 text-red-600"></i>Text Hyperlink
                    </label>
                    <input type="text" name="text_hyperlink" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl shadow-sm focus:border-red-600 focus:ring-4 focus:ring-red-100 transition-all duration-200" placeholder="Contoh: Dokumen Laporan">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-link mr-1 text-red-600"></i>URL Link
                    </label>
                    <input type="url" name="url_link" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl shadow-sm focus:border-red-600 focus:ring-4 focus:ring-red-100 transition-all duration-200" placeholder="https://example.com">
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeLinkModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-3 rounded-xl font-bold transition-all duration-200">
                    Batal
                </button>
                <button type="submit" class="flex-1 bg-gradient-to-r from-red-700 to-red-800 hover:from-red-800 hover:to-red-900 text-white px-4 py-3 rounded-xl font-bold transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openLinkModal(isianId) {
    const modal = document.getElementById('linkModal');
    const form = document.getElementById('linkForm');
    form.action = `/isian/${isianId}/link`;
    modal.classList.remove('hidden');
}

function closeLinkModal() {
    const modal = document.getElementById('linkModal');
    modal.classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('linkModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeLinkModal();
    }
});
</script>
@endif

@endsection