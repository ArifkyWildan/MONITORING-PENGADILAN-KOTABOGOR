@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-list mr-3 text-blue-600"></i>List Isian
                </h1>
                <p class="mt-2 text-gray-600">Daftar semua isian yang tersedia</p>
            </div>
            <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('isian.list') }}" class="flex gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Bagian</label>
                <select name="bagian_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Bagian</option>
                    @foreach($bagians as $bagian)
                    <option value="{{ $bagian->id }}" {{ request('bagian_id') == $bagian->id ? 'selected' : '' }}>
                        {{ $bagian->nama_bagian }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bagian</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daftar Isi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    @if(auth()->user()->isUser())
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($isians as $index => $isian)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $isians->firstItem() + $index }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $isian->bagian->nama_bagian }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ Str::limit($isian->daftar_isi, 50) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($isian->link)
                            <a href="{{ $isian->link->url_link }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-external-link-alt mr-1"></i>{{ $isian->link->text_hyperlink }}
                            </a>
                        @else
                            <span class="text-gray-400 italic">Belum ada link</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($isian->verifikasi)
                            @if($isian->verifikasi->status === 'disetujui')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Disetujui
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>Ditolak
                                </span>
                            @endif
                        @elseif($isian->link)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i>Menunggu Verifikasi
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                <i class="fas fa-minus-circle mr-1"></i>Belum Isi Link
                            </span>
                        @endif
                    </td>
                    @if(auth()->user()->isUser())
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if(!$isian->link)
                            <button onclick="openLinkModal({{ $isian->id }})" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs transition">
                                <i class="fas fa-plus mr-1"></i>Isi Link
                            </button>
                        @else
                            <span class="text-gray-400 text-xs">Link sudah diisi</span>
                        @endif
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada data isian
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="bg-white rounded-lg shadow p-4">
        {{ $isians->links() }}
    </div>
</div>

<!-- Modal Isi Link (Untuk User) -->
@if(auth()->user()->isUser())
<div id="linkModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Tambah Link</h3>
            <button onclick="closeLinkModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="linkForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Text Hyperlink</label>
                <input type="text" name="text_hyperlink" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Dokumen Laporan">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">URL Link</label>
                <input type="url" name="url_link" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="https://example.com">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeLinkModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition">
                    Batal
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
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