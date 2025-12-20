@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-clipboard-check mr-2 text-blue-600"></i>Verifikasi Isian
                </h1>
                <p class="text-gray-600 mt-1">Verifikasi data isian yang sudah diisi link</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Total Menunggu Verifikasi</p>
                <p class="text-3xl font-bold text-orange-600">{{ $isians->total() }}</p>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('isian.verifikasi') }}" class="flex gap-3">
            <select name="bagian_id" class="flex-1 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                <option value="">-- Semua Bagian --</option>
                @foreach($bagians as $bagian)
                <option value="{{ $bagian->id }}" {{ request('bagian_id') == $bagian->id ? 'selected' : '' }}>
                    {{ $bagian->nama_bagian }}
                </option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
            <a href="{{ route('isian.verifikasi') }}" class="border-2 border-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-50 transition">
                <i class="fas fa-redo mr-2"></i>Reset
            </a>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bagian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daftar Isian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($isians as $index => $isian)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $isians->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-blue-600 font-semibold">{{ substr($isian->creator->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $isian->creator->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $isian->creator->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                {{ $isian->bagian->nama_bagian }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 max-w-xs">
                            <div class="truncate">{{ Str::limit($isian->daftar_isi, 60) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($isian->link)
                            <a href="{{ $isian->link->url_link }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline flex items-center">
                                <i class="fas fa-link mr-1"></i>
                                {{ Str::limit($isian->link->text_hyperlink, 20) }}
                            </a>
                            @else
                            <span class="text-gray-400 italic">Belum ada link</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(!$isian->verifikasi)
                            <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i>Pending
                            </span>
                            @elseif($isian->verifikasi->status === 'disetujui')
                            <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Disetujui
                            </span>
                            @else
                            <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>Ditolak
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            @if(!$isian->verifikasi)
                            <button onclick="openVerifikasiModal({{ $isian->id }}, '{{ $isian->bagian->nama_bagian }}', '{{ Str::limit($isian->daftar_isi, 50) }}')" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                                <i class="fas fa-clipboard-check mr-1"></i>Verifikasi
                            </button>
                            @else
                            <button onclick="showVerifikasiDetail({{ $isian->id }})" 
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                                <i class="fas fa-eye mr-1"></i>Lihat Detail
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3"></i>
                            <p class="text-lg font-medium">Tidak ada data yang perlu diverifikasi</p>
                            <p class="text-sm mt-1">Semua isian sudah diverifikasi atau belum ada link yang ditambahkan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($isians->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $isians->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Verifikasi -->
<div id="verifikasiModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
        <div class="flex items-center justify-between mb-4 pb-3 border-b">
            <h3 class="text-xl font-bold text-gray-900">
                <i class="fas fa-clipboard-check mr-2 text-blue-600"></i>Form Verifikasi
            </h3>
            <button onclick="closeVerifikasiModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <form id="verifikasiForm" method="POST" class="space-y-4">
            @csrf
            
            <!-- Info Isian -->
            <div class="bg-blue-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">Bagian:</p>
                <p class="font-semibold text-gray-900" id="modalBagian"></p>
                <p class="text-sm text-gray-600 mt-2 mb-1">Daftar Isi:</p>
                <p class="text-gray-700" id="modalDaftarIsi"></p>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-flag mr-1"></i>Status Verifikasi <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative flex items-center justify-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-green-50 hover:border-green-500 transition">
                        <input type="radio" name="status" value="disetujui" required class="sr-only peer">
                        <div class="text-center peer-checked:text-green-700">
                            <i class="fas fa-check-circle text-3xl mb-2 peer-checked:text-green-600"></i>
                            <p class="font-semibold">Disetujui</p>
                        </div>
                        <div class="hidden peer-checked:block absolute inset-0 border-2 border-green-600 bg-green-50 rounded-lg"></div>
                    </label>
                    <label class="relative flex items-center justify-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-red-50 hover:border-red-500 transition">
                        <input type="radio" name="status" value="ditolak" required class="sr-only peer">
                        <div class="text-center peer-checked:text-red-700">
                            <i class="fas fa-times-circle text-3xl mb-2 peer-checked:text-red-600"></i>
                            <p class="font-semibold">Ditolak</p>
                        </div>
                        <div class="hidden peer-checked:block absolute inset-0 border-2 border-red-600 bg-red-50 rounded-lg"></div>
                    </label>
                </div>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-comment-dots mr-1"></i>Keterangan <span class="text-red-500">*</span>
                </label>
                <textarea name="deskripsi" rows="5" required placeholder="Masukkan keterangan verifikasi..." 
                          class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"></textarea>
                <p class="mt-1 text-xs text-gray-500">Jelaskan alasan persetujuan atau penolakan</p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4 border-t">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition shadow-md hover:shadow-lg">
                    <i class="fas fa-paper-plane mr-2"></i>Kirim Verifikasi
                </button>
                <button type="button" onclick="closeVerifikasiModal()" class="flex-1 border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail Verifikasi -->
<div id="detailModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
        <div class="flex items-center justify-between mb-4 pb-3 border-b">
            <h3 class="text-xl font-bold text-gray-900">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i>Detail Verifikasi
            </h3>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <div id="detailContent" class="space-y-4">
            <!-- Content will be loaded via AJAX -->
        </div>

        <div class="mt-6 pt-4 border-t">
            <button onclick="closeDetailModal()" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                <i class="fas fa-times mr-2"></i>Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openVerifikasiModal(isianId, bagian, daftarIsi) {
    document.getElementById('modalBagian').textContent = bagian;
    document.getElementById('modalDaftarIsi').textContent = daftarIsi;
    document.getElementById('verifikasiForm').action = `/isian/${isianId}/verifikasi`;
    document.getElementById('verifikasiModal').classList.remove('hidden');
}

function closeVerifikasiModal() {
    document.getElementById('verifikasiModal').classList.add('hidden');
    document.getElementById('verifikasiForm').reset();
}

function showVerifikasiDetail(isianId) {
    fetch(`/isian/${isianId}/verifikasi/detail`)
        .then(response => response.json())
        .then(data => {
            const statusClass = data.status === 'disetujui' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
            const statusIcon = data.status === 'disetujui' ? 'fa-check-circle' : 'fa-times-circle';
            
            document.getElementById('detailContent').innerHTML = `
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-2">Status:</p>
                    <span class="px-4 py-2 inline-flex items-center text-sm font-semibold rounded-full ${statusClass}">
                        <i class="fas ${statusIcon} mr-2"></i>${data.status.charAt(0).toUpperCase() + data.status.slice(1)}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-2">Keterangan:</p>
                    <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">${data.deskripsi}</p>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-gray-600 mb-1">Diverifikasi oleh:</p>
                        <p class="font-semibold text-gray-900">${data.verifikator}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-gray-600 mb-1">Tanggal:</p>
                        <p class="font-semibold text-gray-900">${data.verified_at}</p>
                    </div>
                </div>
            `;
            document.getElementById('detailModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat detail verifikasi');
        });
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const verifikasiModal = document.getElementById('verifikasiModal');
    const detailModal = document.getElementById('detailModal');
    if (event.target == verifikasiModal) {
        closeVerifikasiModal();
    }
    if (event.target == detailModal) {
        closeDetailModal();
    }
}
</script>
@endpush
@endsection