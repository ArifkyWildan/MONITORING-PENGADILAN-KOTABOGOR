@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center bg-white rounded-lg shadow p-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-list-alt mr-2 text-blue-600"></i>Daftar Isian
            </h1>
            <p class="text-gray-600 mt-1">Kelola dan monitor semua data isian</p>
        </div>
        @can('create', App\Models\Isian::class)
        <a href="{{ route('isian.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
            <i class="fas fa-plus mr-2"></i>Tambah Isian
        </a>
        @endcan
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('isian.daftar') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Bagian</label>
                <select name="bagian_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">Semua Bagian</option>
                    @foreach($bagians as $bagian)
                    <option value="{{ $bagian->id }}" {{ request('bagian_id') == $bagian->id ? 'selected' : '' }}>
                        {{ $bagian->nama_bagian }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">Semua Status</option>
                    <option value="belum_isi_link" {{ request('status') == 'belum_isi_link' ? 'selected' : '' }}>Belum Isi Link</option>
                    <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white px-6 py-2 rounded-lg font-medium transition">
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Bagian</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daftar Isi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembuat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($isians as $index => $isian)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $isians->firstItem() + $index }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($isian->bagian)
                            <span class="px-3 py-1 text-sm font-medium bg-blue-100 text-blue-800 rounded-full">
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
                        {{ $isian->creator->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($isian->hasLink())
                            <a href="{{ $isian->link->url_link }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline">
                                <i class="fas fa-external-link-alt mr-1"></i>{{ $isian->link->text_hyperlink }}
                            </a>
                        @else
                            @can('addLink', $isian)
                            <button onclick="openLinkModal({{ $isian->id }})" class="inline-flex items-center px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded-lg font-medium transition">
                                <i class="fas fa-link mr-1"></i>Isi Link
                            </button>
                            @else
                            <span class="text-gray-400 text-sm">Belum ada link</span>
                            @endcan
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($isian->isVerified())
                            <button onclick="showVerifikasi({{ $isian->id }})" class="flex items-center">
                                @if($isian->verifikasi->status == 'disetujui')
                                <span class="px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full cursor-pointer hover:bg-green-200">
                                    <i class="fas fa-check-circle mr-1"></i>Disetujui
                                </span>
                                @else
                                <span class="px-3 py-1 text-sm font-medium bg-red-100 text-red-800 rounded-full cursor-pointer hover:bg-red-200">
                                    <i class="fas fa-times-circle mr-1"></i>Ditolak
                                </span>
                                @endif
                            </button>
                        @elseif($isian->hasLink())
                            <span class="px-3 py-1 text-sm font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                <i class="fas fa-clock mr-1"></i>Menunggu
                            </span>
                        @else
                            <span class="px-3 py-1 text-sm font-medium bg-gray-100 text-gray-800 rounded-full">
                                <i class="fas fa-minus-circle mr-1"></i>Belum Isi Link
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="flex space-x-2">
                            @can('update', $isian)
                            <a href="{{ route('isian.edit', $isian) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endcan
                            
                            @can('delete', $isian)
                            <form action="{{ route('isian.destroy', $isian) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-2"></i>
                        <p>Tidak ada data isian</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t">
            {{ $isians->links() }}
        </div>
    </div>
</div>

<!-- Link Modal -->
<div id="linkModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Isi Link Isian</h3>
            <button onclick="closeLinkModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="linkForm" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-text-width mr-1"></i>Text Hyperlink
                    </label>
                    <input type="text" name="text_hyperlink" required placeholder="Contoh: Dokumen Laporan" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-link mr-1"></i>URL Link
                    </label>
                    <input type="url" name="url_link" required placeholder="https://example.com/dokumen" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeLinkModal()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-1"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Verifikasi Detail Modal -->
<div id="verifikasiModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Detail Verifikasi</h3>
            <button onclick="closeVerifikasiModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="verifikasiContent" class="space-y-3">
            <!-- Content loaded via AJAX -->
        </div>
        <div class="mt-6 flex justify-end">
            <button onclick="closeVerifikasiModal()" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900">
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
                <div class="p-3 ${statusClass} rounded-lg">
                    <p class="font-semibold"><i class="fas ${statusIcon} mr-2"></i>Status: ${data.status === 'disetujui' ? 'Disetujui' : 'Ditolak'}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-1">Deskripsi:</p>
                    <p class="text-gray-900">${data.deskripsi}</p>
                </div>
                <div class="text-sm text-gray-600">
                    <p><strong>Diverifikasi oleh:</strong> ${data.verifikator}</p>
                    <p><strong>Tanggal:</strong> ${data.verified_at}</p>
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