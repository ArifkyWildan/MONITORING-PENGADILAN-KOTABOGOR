@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 space-y-6">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-800 to-red-900 rounded-xl shadow-2xl p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full -ml-24 -mb-24"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="bg-white bg-opacity-20 rounded-lg p-3">
                            <i class="fas fa-clipboard-check text-3xl text-white"></i>
                        </div>
                        <h1 class="text-3xl font-bold text-white">Verifikasi Isian</h1>
                    </div>
                    <p class="text-red-100 text-lg ml-1">
                        @if(auth()->user()->isVerifikator() && auth()->user()->bagian)
                            Verifikasi data isian untuk bagian <span class="font-bold bg-white bg-opacity-20 px-2 py-1 rounded">{{ auth()->user()->bagian->nama_bagian }}</span>
                        @else
                            Verifikasi data isian yang sudah diisi link
                        @endif
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-xl p-6 backdrop-blur-sm">
                    <p class="text-sm text-red-100 font-semibold">Total Menunggu Verifikasi</p>
                    <p class="text-4xl font-bold text-white mt-1">{{ $isians->total() }}</p>
                    @if(auth()->user()->isVerifikator() && auth()->user()->bagian)
                        <p class="text-xs text-red-100 mt-1">Bagian {{ auth()->user()->bagian->nama_bagian }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Filter -->
        @if(!auth()->user()->isVerifikator() || !auth()->user()->bagian_id)
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-4">
                <div class="bg-red-100 rounded-lg p-2 mr-3">
                    <i class="fas fa-filter text-red-700"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Filter Data</h2>
            </div>
            <form method="GET" action="{{ route('isian.verifikasi') }}" class="flex flex-col md:flex-row gap-3">
                <select name="bagian_id" class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-xl shadow-sm focus:border-red-600 focus:ring-4 focus:ring-red-100 transition-all duration-200">
                    <option value="">-- Semua Bagian --</option>
                    @foreach($bagians as $bagian)
                    <option value="{{ $bagian->id }}" {{ request('bagian_id') == $bagian->id ? 'selected' : '' }}>
                        {{ $bagian->nama_bagian }}
                    </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-gradient-to-r from-red-700 to-red-800 hover:from-red-800 hover:to-red-900 text-white px-8 py-3 rounded-xl font-bold transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-filter mr-2"></i>Terapkan Filter
                </button>
                <a href="{{ route('isian.verifikasi') }}" class="border-2 border-gray-300 text-gray-700 px-8 py-3 rounded-xl font-bold hover:bg-gray-50 transition-all duration-200">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
            </form>
        </div>
        @endif

        <!-- Info Badge for Verifikator -->
        @if(auth()->user()->isVerifikator() && auth()->user()->bagian)
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-500 p-5 rounded-xl shadow-md">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="bg-blue-500 rounded-lg p-3">
                        <i class="fas fa-info-circle text-white text-2xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-bold text-blue-900">
                        <span class="text-blue-700">Info:</span> Anda hanya dapat memverifikasi isian dari bagian <span class="bg-blue-200 px-2 py-1 rounded font-bold">{{ auth()->user()->bagian->nama_bagian }}</span>
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-red-700 to-red-800">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">No</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Bagian</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Daftar Isian</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Link</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($isians as $index => $isian)
                        <tr class="hover:bg-red-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                {{ $isians->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-md">
                                            <span class="text-white font-bold">{{ substr($isian->creator->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $isian->creator->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $isian->creator->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-4 py-2 inline-flex text-xs leading-5 font-bold rounded-full bg-purple-100 text-purple-800">
                                    {{ $isian->bagian->nama_bagian }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs">
                                <div class="truncate">{{ Str::limit($isian->daftar_isi, 60) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($isian->link)
                                <a href="{{ $isian->link->url_link }}" target="_blank" class="text-red-700 hover:text-red-900 hover:underline flex items-center font-semibold">
                                    <i class="fas fa-link mr-1"></i>
                                    {{ Str::limit($isian->link->text_hyperlink, 20) }}
                                </a>
                                @else
                                <span class="text-gray-400 italic">Belum ada link</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(!$isian->verifikasi)
                                <span class="px-4 py-2 inline-flex items-center text-xs leading-5 font-bold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>Pending
                                </span>
                                @elseif($isian->verifikasi->status === 'disetujui')
                                <span class="px-4 py-2 inline-flex items-center text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Disetujui
                                </span>
                                @else
                                <span class="px-4 py-2 inline-flex items-center text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>Ditolak
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                @if(!$isian->verifikasi)
                                <button onclick="openVerifikasiModal({{ $isian->id }}, '{{ $isian->bagian->nama_bagian }}', '{{ Str::limit($isian->daftar_isi, 50) }}')" 
                                        class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-5 py-2 rounded-xl font-bold transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-clipboard-check mr-1"></i>Verifikasi
                                </button>
                                @else
                                <button onclick="showVerifikasiDetail({{ $isian->id }})" 
                                        class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-5 py-2 rounded-xl font-bold transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-eye mr-1"></i>Lihat Detail
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center text-gray-500">
                                <i class="fas fa-inbox text-6xl mb-4 text-gray-300"></i>
                                <p class="text-xl font-bold">Tidak ada data yang perlu diverifikasi</p>
                                <p class="text-sm mt-2">
                                    @if(auth()->user()->isVerifikator() && auth()->user()->bagian)
                                        Semua isian dari bagian {{ auth()->user()->bagian->nama_bagian }} sudah diverifikasi atau belum ada link yang ditambahkan
                                    @else
                                        Semua isian sudah diverifikasi atau belum ada link yang ditambahkan
                                    @endif
                                </p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($isians->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $isians->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Verifikasi -->
<div id="verifikasiModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-8 border w-full max-w-2xl shadow-2xl rounded-xl bg-white">
        <div class="flex items-center justify-between mb-6 pb-4 border-b-2 border-gray-200">
            <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                <div class="bg-red-100 rounded-lg p-3 mr-3">
                    <i class="fas fa-clipboard-check text-2xl text-red-700"></i>
                </div>
                Form Verifikasi
            </h3>
            <button onclick="closeVerifikasiModal()" class="text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-lg transition-all duration-150">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <form id="verifikasiForm" method="POST" class="space-y-6">
            @csrf
            
            <!-- Info Isian -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-5 rounded-xl border-l-4 border-blue-500">
                <p class="text-sm text-gray-600 font-semibold mb-1">Bagian:</p>
                <p class="font-bold text-gray-900 text-lg" id="modalBagian"></p>
                <p class="text-sm text-gray-600 font-semibold mt-3 mb-1">Daftar Isi:</p>
                <p class="text-gray-700" id="modalDaftarIsi"></p>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-3">
                    <i class="fas fa-flag mr-1 text-red-600"></i>Status Verifikasi <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex items-center justify-center p-6 border-3 border-gray-300 rounded-xl cursor-pointer hover:bg-green-50 hover:border-green-500 transition-all duration-200 group">
                        <input type="radio" name="status" value="disetujui" required class="sr-only peer">
                        <div class="text-center peer-checked:text-green-700">
                            <i class="fas fa-check-circle text-4xl mb-3 text-gray-400 group-hover:text-green-600 peer-checked:text-green-600"></i>
                            <p class="font-bold text-lg">Disetujui</p>
                        </div>
                        <div class="hidden peer-checked:block absolute inset-0 border-3 border-green-600 bg-green-50 rounded-xl"></div>
                    </label>
                    <label class="relative flex items-center justify-center p-6 border-3 border-gray-300 rounded-xl cursor-pointer hover:bg-red-50 hover:border-red-500 transition-all duration-200 group">
                        <input type="radio" name="status" value="ditolak" required class="sr-only peer">
                        <div class="text-center peer-checked:text-red-700">
                            <i class="fas fa-times-circle text-4xl mb-3 text-gray-400 group-hover:text-red-600 peer-checked:text-red-600"></i>
                            <p class="font-bold text-lg">Ditolak</p>
                        </div>
                        <div class="hidden peer-checked:block absolute inset-0 border-3 border-red-600 bg-red-50 rounded-xl"></div>
                    </label>
                </div>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    <i class="fas fa-comment-dots mr-1 text-red-600"></i>Keterangan <span class="text-red-500">*</span>
                </label>
                <textarea name="deskripsi" rows="5" required placeholder="Masukkan keterangan verifikasi..." 
                          class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl shadow-sm focus:border-red-600 focus:ring-4 focus:ring-red-100 transition-all duration-200"></textarea>
                <p class="mt-2 text-xs text-gray-500 flex items-center">
                    <i class="fas fa-info-circle text-red-600 mr-1"></i>
                    Jelaskan alasan persetujuan atau penolakan secara detail
                </p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4 border-t-2 border-gray-200">
                <button type="submit" class="flex-1 bg-gradient-to-r from-red-700 to-red-800 hover:from-red-800 hover:to-red-900 text-white px-6 py-4 rounded-xl font-bold transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-paper-plane mr-2"></i>Kirim Verifikasi
                </button>
                <button type="button" onclick="closeVerifikasiModal()" class="flex-1 border-2 border-gray-300 text-gray-700 px-6 py-4 rounded-xl font-bold hover:bg-gray-50 transition-all duration-200">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail Verifikasi -->
<div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-8 border w-full max-w-2xl shadow-2xl rounded-xl bg-white">
        <div class="flex items-center justify-between mb-6 pb-4 border-b-2 border-gray-200">
            <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                <div class="bg-red-100 rounded-lg p-3 mr-3">
                    <i class="fas fa-info-circle text-2xl text-red-700"></i>
                </div>
                Detail Verifikasi
            </h3>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-lg transition-all duration-150">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <div id="detailContent" class="space-y-5">
            <!-- Content will be loaded via AJAX -->
        </div>

        <div class="mt-6 pt-4 border-t-2 border-gray-200">
            <button onclick="closeDetailModal()" class="w-full bg-gray-800 hover:bg-gray-900 text-white px-6 py-4 rounded-xl font-bold transition-all duration-200">
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
            const statusClass = data.status === 'disetujui' ? 'bg-green-100 text-green-800 border-green-500' : 'bg-red-100 text-red-800 border-red-500';
            const statusIcon = data.status === 'disetujui' ? 'fa-check-circle text-green-600' : 'fa-times-circle text-red-600';
            
            document.getElementById('detailContent').innerHTML = `
                <div class="${statusClass} p-5 rounded-xl border-l-4">
                    <p class="text-sm font-semibold mb-1">Status:</p>
                    <span class="text-lg font-bold flex items-center">
                        <i class="fas ${statusIcon} mr-2 text-2xl"></i>${data.status.charAt(0).toUpperCase() + data.status.slice(1)}
                    </span>
                </div>
                <div class="bg-gray-50 p-5 rounded-xl">
                    <p class="text-sm font-bold text-gray-700 mb-2">Keterangan:</p>
                    <p class="text-gray-900">${data.deskripsi}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl">
                        <p class="text-sm text-gray-600 font-semibold mb-1">Diverifikasi oleh:</p>
                        <p class="font-bold text-gray-900">${data.verifikator}</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-xl">
                        <p class="text-sm text-gray-600 font-semibold mb-1">Tanggal:</p>
                        <p class="font-bold text-gray-900">${data.verified_at}</p>
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