<?php

namespace App\Http\Controllers;

use App\Models\Isian;
use App\Models\Bagian;
use App\Models\Link;
use App\Models\Verifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class IsianController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display dashboard with statistics
     */
    public function index()
    {
        $this->authorize('viewStatistics', Isian::class);

        $statistics = [
            'total' => Isian::count(),
            'belum_isi_link' => Isian::doesntHave('link')->count(),
            'disetujui' => Isian::whereHas('verifikasi', function($q) {
                $q->where('status', 'disetujui');
            })->count(),
            'ditolak' => Isian::whereHas('verifikasi', function($q) {
                $q->where('status', 'ditolak');
            })->count(),
        ];

        return view('isian.index', compact('statistics'));
    }

    /**
     * Display list of all isians (Daftar Isian)
     */
    public function daftar(Request $request)
    {
        // Semua authenticated user bisa akses
        $query = Isian::with(['bagian', 'link', 'verifikasi.verifikator', 'creator']);

        // Filter by bagian
        if ($request->filled('bagian_id')) {
            $query->where('bagian_id', $request->bagian_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'belum_isi_link':
                    $query->doesntHave('link');
                    break;
                case 'menunggu_verifikasi':
                    $query->has('link')->doesntHave('verifikasi');
                    break;
                case 'disetujui':
                    $query->whereHas('verifikasi', fn($q) => $q->where('status', 'disetujui'));
                    break;
                case 'ditolak':
                    $query->whereHas('verifikasi', fn($q) => $q->where('status', 'ditolak'));
                    break;
            }
        }

        $isians = $query->latest()->paginate(15);
        $bagians = Bagian::all();

        return view('isian.daftar', compact('isians', 'bagians'));
    }

    /**
     * Display simple list (List Isian - Read Only)
     */
    public function list(Request $request)
    {
        $this->authorize('viewAny', Isian::class);

        $query = Isian::with(['bagian', 'link', 'verifikasi']);

        if ($request->filled('bagian_id')) {
            $query->where('bagian_id', $request->bagian_id);
        }

        $isians = $query->latest()->paginate(15);
        $bagians = Bagian::all();

        return view('isian.list', compact('isians', 'bagians'));
    }

    /**
     * Display verifikasi page (Verifikator only)
     */
    public function verifikasi(Request $request)
    {
        $this->authorize('verify', Isian::class);

        $user = auth()->user();
        
        $query = Isian::with(['bagian', 'link', 'verifikasi', 'creator'])
            ->has('link')
            ->doesntHave('verifikasi');

        // FILTER BERDASARKAN BAGIAN VERIFIKATOR
        if ($user->isVerifikator() && $user->bagian_id) {
            // Verifikator hanya bisa lihat isian dari bagiannya
            $query->where('bagian_id', $user->bagian_id);
        }

        // Filter tambahan dari request
        if ($request->filled('bagian_id')) {
            // Pastikan verifikator tidak bisa filter ke bagian lain
            if ($user->isVerifikator() && $user->bagian_id && $request->bagian_id != $user->bagian_id) {
                return redirect()->route('isian.verifikasi')
                    ->with('error', 'Anda hanya dapat memverifikasi isian dari bagian Anda sendiri');
            }
            $query->where('bagian_id', $request->bagian_id);
        }

        $isians = $query->latest()->paginate(15);
        
        // Bagians - untuk verifikator hanya tampilkan bagiannya
        if ($user->isVerifikator() && $user->bagian_id) {
            $bagians = Bagian::where('id', $user->bagian_id)->get();
        } else {
            $bagians = Bagian::all();
        }

        return view('isian.verifikasi', compact('isians', 'bagians'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $this->authorize('create', Isian::class);
        $bagians = Bagian::all();
        return view('isian.create', compact('bagians'));
    }

    /**
     * Store new isian
     */
    public function store(Request $request)
    {
        $this->authorize('create', Isian::class);

        $validated = $request->validate([
            'bagian_id' => 'required|exists:bagians,id',
            'daftar_isi' => 'required|string'
        ]);

        $isian = Isian::create([
            'bagian_id' => $validated['bagian_id'],
            'daftar_isi' => $validated['daftar_isi'],
            'created_by' => auth()->id()
        ]);

        return redirect()->route('isian.daftar')
            ->with('success', 'Isian berhasil ditambahkan');
    }

    /**
     * Show edit form
     */
    public function edit(Isian $isian)
    {
        $this->authorize('update', $isian);
        $bagians = Bagian::all();
        return view('isian.edit', compact('isian', 'bagians'));
    }

    /**
     * Update isian
     */
    public function update(Request $request, Isian $isian)
    {
        $this->authorize('update', $isian);

        $validated = $request->validate([
            'bagian_id' => 'required|exists:bagians,id',
            'daftar_isi' => 'required|string'
        ]);

        $isian->update($validated);

        return redirect()->route('isian.daftar')
            ->with('success', 'Isian berhasil diupdate');
    }

    /**
     * Delete isian
     */
    public function destroy(Isian $isian)
    {
        $this->authorize('delete', $isian);
        $isian->delete();

        return redirect()->route('isian.daftar')
            ->with('success', 'Isian berhasil dihapus');
    }

    /**
     * Store link for isian
     */
    public function storeLink(Request $request, Isian $isian)
    {
        $this->authorize('addLink', $isian);

        $validated = $request->validate([
            'text_hyperlink' => 'required|string|max:255',
            'url_link' => 'required|url'
        ]);

        Link::create([
            'isian_id' => $isian->id,
            'text_hyperlink' => $validated['text_hyperlink'],
            'url_link' => $validated['url_link'],
            'created_by' => auth()->id()
        ]);

        return back()->with('success', 'Link berhasil ditambahkan');
    }

    /**
     * Store verifikasi
     */
    public function storeVerifikasi(Request $request, Isian $isian)
    {
        $this->authorize('verify', Isian::class);

        $user = auth()->user();

        // Cek apakah verifikator bisa memverifikasi bagian ini
        if ($user->isVerifikator() && !$user->canVerifyBagian($isian->bagian_id)) {
            return back()->with('error', 'Anda hanya dapat memverifikasi isian dari bagian Anda sendiri');
        }

        // Validasi: Isian harus punya link dan belum diverifikasi
        if (!$isian->hasLink()) {
            return back()->with('error', 'Isian harus memiliki link sebelum diverifikasi');
        }

        if ($isian->isVerified()) {
            return back()->with('error', 'Isian sudah diverifikasi sebelumnya');
        }

        $validated = $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'deskripsi' => 'required|string'
        ]);

        Verifikasi::create([
            'isian_id' => $isian->id,
            'status' => $validated['status'],
            'deskripsi' => $validated['deskripsi'],
            'verifikator_id' => auth()->id(),
            'verified_at' => now()
        ]);

        return redirect()->route('isian.verifikasi')
            ->with('success', 'Verifikasi berhasil disimpan');
    }

    /**
     * Show verifikasi detail
     */
    public function showVerifikasi(Isian $isian)
    {
        $this->authorize('view', $isian);
        
        if (!$isian->isVerified()) {
            abort(404);
        }

        return response()->json([
            'status' => $isian->verifikasi->status,
            'deskripsi' => $isian->verifikasi->deskripsi,
            'verifikator' => $isian->verifikasi->verifikator->name,
            'verified_at' => $isian->verifikasi->verified_at->format('d M Y H:i')
        ]);
    }
}