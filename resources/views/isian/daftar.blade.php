@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-800 to-red-900 rounded-xl shadow-2xl p-8 mb-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full -ml-24 -mb-24"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="bg-white bg-opacity-20 rounded-lg p-3">
                            <i class="fas fa-list-alt text-3xl text-white"></i>
                        </div>
                        <h1 class="text-3xl font-bold text-white">Daftar Isian</h1>
                    </div>
                    <p class="text-red-100 text-lg ml-1">Kelola dan monitor semua data isian</p>
                </div>
                @can('create', App\Models\Isian::class)
                <a href="{{ route('isian.create') }}" class="bg-white hover:bg-red-50 text-red-800 px-6 py-3 rounded-xl font-bold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>Tambah Isian
                </a>
                @endcan
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex items-center mb-4">
                <div class="bg-red-100 rounded-lg p-2 mr-3">
                    <i class="fas fa-filter text-red-700"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Filter Data</h2>
            </div>
            <form method="GET" action="{{ route('isian.daftar') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
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

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Filter Status</label>
                    <select name="status" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl shadow-sm focus:border-red-600 focus:ring-4 focus:ring-red-100 transition-all duration-200">
                        <option value="">Semua Status</option>
                        <option value="belum_isi_link" {{ request('status') == 'belum_isi_link' ? 'selected' : '' }}>Belum Isi Link</option>
                        <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gradient-to-r from-red-700 to-red-800 hover:from-red-800 hover:to-red-900 text-white px-6 py-3 rounded-xl font-bold transition-all duration-200 shadow-lg hover:shadow-xl">
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
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Nama Bagian</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Daftar Isi</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Pembuat</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Link</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($isians as $index => $isian)
                        <tr class="hover:bg-red-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                {{ $isians->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($isian->bagian)
                                    <span class="px-4 py-2 text-sm font-bold bg-red-100 text-red-800 rounded-full">
                                        {{ $isian->bagian->nama_bagian }}
                                    </span>
                                @else
                                    <span class="text-red-500 text-sm">Bagian tidak ditemukan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ Str::limit($isian->daftar_isi, 100) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center mr-2">
                                        <span class="text-red-700 font-bold text-xs">{{ substr($isian->creator->name ?? 'N', 0, 1) }}</span>
                                    </div>
                                    {{ $isian->creator->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($isian->hasLink())
                                    <a href="{{ $isian->link->url_link }}" target="_blank" class="text-red-700 hover:text-red-900 hover:underline font-semibold">
                                        <i class="fas fa-external-link-alt mr-1"></i>{{ $isian->link->text_hyperlink }}
                                    </a>
                                @else
                                    @can('addLink', $isian)
                                    <button onclick="openLinkModal({{ $isian->id }})" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded-lg font-bold transition-all duration-200 shadow-md hover:shadow-lg">
                                        <i class="fas fa-link mr-1"></i>Isi Link
                                    </button>
                                    @else
                                    <span class="text-gray-400 text-sm italic">Belum ada link</span>
                                    @endcan
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($isian->isVerified())
                                    <button onclick="showVerifikasi({{ $isian->id }})" class="flex items-center">
                                        @if($isian->verifikasi->status == 'disetujui')
                                        <span class="px-4 py-2 text-sm font-bold bg-green-100 text-green-800 rounded-full cursor-pointer hover:bg-green-200 transition-colors duration-150">
                                            <i class="fas fa-check-circle mr-1"></i>Disetujui
                                        </span>
                                        @else
                                        <span class="px-4 py-2 text-sm font-bold bg-red-100 text-red-800 rounded-full cursor-pointer hover:bg-red-200 transition-colors duration-150">
                                            <i class="fas fa-times-circle mr-1"></i>Ditolak
                                        </span>
                                        @endif
                                    </button>
                                @elseif($isian->hasLink())
                                    <span class="px-4 py-2 text-sm font-bold bg-yellow-100 text-yellow-800 rounded-full">
                                        <i class="fas fa-clock mr-1"></i>Menunggu
                                    </span>
                                @else
                                    <span class="px-4 py-2 text-sm font-bold bg-gray-100 text-gray-800 rounded-full">
                                        <i class="fas fa-minus-circle mr-1"></i>Belum Isi Link
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex space-x-2">
                                    @can('update', $isian)
                                    <a href="{{ route('isian.edit', $isian) }}" class="text-blue-600 hover:text-blue-800 p-2 hover:bg-blue-50 rounded-lg transition-all duration-150">
                                        <i class="fas fa-edit text-lg"></i>
                                    </a>
                                    @endcan
                                    
                                    @can('delete', $isian)
                                    <form action="{{ route('isian.destroy', $isian) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 p-2 hover:bg-red-50 rounded-lg transition-all duration-150">
                                            <i class="fas fa-trash text-lg"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-5xl mb-3 text-gray-300"></i>
                                <p class="text-lg font-semibold">Tidak ada data isian</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t">
                {{ $isians->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Link Modal -->
<div id="linkModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4 shadow-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                <div class="bg-red-100 rounded-lg p-2 mr-3">
                    <i class="fas fa-link text-red-700"></i>
                </div>
                Isi Link Isian
            </h3>
            <button onclick="closeLinkModal()" class="text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-lg transition-all duration-150">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <form id="linkForm" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-text-width mr-1 text-red-600"></i>Text Hyperlink
                    </label>
                    <input type="text" name="text_hyperlink" required placeholder="Contoh: Dokumen Laporan" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl shadow-sm focus:border-red-600 focus:ring-4 focus:ring-red-100 transition-all duration-200">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-link mr-1 text-red-600"></i>URL Link
                    </label>
                    <input type="url" name="url_link" required placeholder="https://example.com/dokumen" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl shadow-sm focus:border-red-600 focus:ring-4 focus:ring-red-100 transition-all duration-200">
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="button" onclick="closeLinkModal()" class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-xl hover:bg-gray-50 font-bold transition-all duration-200">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-red-700 to-red-800 text-white rounded-xl hover:from-red-800 hover:to-red-900 font-bold transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-save mr-1"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Verifikasi Detail Modal -->
<div id="verifikasiModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4 shadow-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                <div class="bg-red-100 rounded-lg p-2 mr-3">
                    <i class="fas fa-info-circle text-red-700"></i>
                </div>
                Detail Verifikasi
            </h3>
            <button onclick="closeVerifikasiModal()" class="text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-lg transition-all duration-150">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <div id="verifikasiContent" class="space-y-4">
            <!-- Content loaded via AJAX -->
        </div>
        <div class="mt-6">
            <button onclick="closeVerifikasiModal()" class="w-full px-4 py-3 bg-gray-800 text-white rounded-xl hover:bg-gray-900 font-bold transition-all duration-200">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
function openLinkModal(isianId) {
    const form = document.getElementById('linkForm');
    form.action = `/isian/${isianId}/link`;
    document.getElementById('linkModal').classList.remove('hidden');
}

function closeLinkModal() {
    document.getElementById('linkModal').classList.add('hidden');
    document.getElementById('linkForm').reset();
}

function showVerifikasi(isianId) {
    fetch(`/isian/${isianId}/verifikasi/detail`)
        .then(res => res.json())
        .then(data => {
            const statusClass = data.status === 'disetujui' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
            const statusIcon = data.status === 'disetujui' ? 'fa-check-circle' : 'fa-times-circle';
            
            document.getElementById('verifikasiContent').innerHTML = `
                <div class="p-4 ${statusClass} rounded-xl">
                    <p class="font-bold text-lg"><i class="fas ${statusIcon} mr-2"></i>Status: ${data.status === 'disetujui' ? 'Disetujui' : 'Ditolak'}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl">
                    <p class="text-sm font-bold text-gray-700 mb-2">Deskripsi:</p>
                    <p class="text-gray-900">${data.deskripsi}</p>
                </div>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="bg-gray-50 p-3 rounded-xl">
                        <p class="text-gray-600 font-semibold">Diverifikasi oleh:</p>
                        <p class="text-gray-900 font-bold">${data.verifikator}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-xl">
                        <p class="text-gray-600 font-semibold">Tanggal:</p>
                        <p class="text-gray-900 font-bold">${data.verified_at}</p>
                    </div>
                </div>
            `;
            document.getElementById('verifikasiModal').classList.remove('hidden');
        });
}

function closeVerifikasiModal() {
    document.getElementById('verifikasiModal').classList.add('hidden');
}

// Close modal on outside click
document.getElementById('linkModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeLinkModal();
});

document.getElementById('verifikasiModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeVerifikasiModal();
});
</script>
@endsection