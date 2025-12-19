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
        // TIDAK PERLU AUTHORIZATION - semua user yang login bisa akses
        
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

        return view('dashboard', compact('statistics'));
    }

    /**
     * Display list of all isians (Daftar Isian)
     */
    public function daftar(Request $request)
    {
        // Cek manual: hanya admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya admin yang bisa mengakses halaman ini.');
        }

        $query = Isian::with(['bagian', 'link', 'verifikasi.verifikator']);

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
        // Semua user bisa akses, tidak perlu cek authorization
        
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
        // Cek manual: hanya verifikator
        if (!auth()->user()->isVerifikator()) {
            abort(403, 'Hanya verifikator yang bisa mengakses halaman ini.');
        }

        $query = Isian::with(['bagian', 'link', 'verifikasi'])
            ->has('link')
            ->doesntHave('verifikasi');

        if ($request->filled('bagian_id')) {
            $query->where('bagian_id', $request->bagian_id);
        }

        $isians = $query->latest()->paginate(15);
        $bagians = Bagian::all();

        return view('isian.verifikasi', compact('isians', 'bagians'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        // Cek manual: hanya admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya admin yang bisa membuat isian baru.');
        }
        
        $bagians = Bagian::all();
        return view('isian.create', compact('bagians'));
    }

    /**
     * Store new isian
     */
    public function store(Request $request)
    {
        // Cek manual: hanya admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya admin yang bisa membuat isian baru.');
        }

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
        // Cek manual: hanya admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya admin yang bisa mengedit isian.');
        }
        
        $bagians = Bagian::all();
        return view('isian.edit', compact('isian', 'bagians'));
    }

    /**
     * Update isian
     */
    public function update(Request $request, Isian $isian)
    {
        // Cek manual: hanya admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya admin yang bisa mengedit isian.');
        }

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
        // Cek manual: hanya admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya admin yang bisa menghapus isian.');
        }
        
        $isian->delete();

        return redirect()->route('isian.daftar')
            ->with('success', 'Isian berhasil dihapus');
    }

    /**
     * Store link for isian
     */
    public function storeLink(Request $request, Isian $isian)
    {
        // Cek manual: hanya user biasa
        if (!auth()->user()->isUser()) {
            abort(403, 'Hanya user yang bisa menambah link.');
        }

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
        // Cek manual: hanya verifikator
        if (!auth()->user()->isVerifikator()) {
            abort(403, 'Hanya verifikator yang bisa memverifikasi.');
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

        return back()->with('success', 'Verifikasi berhasil disimpan');
    }

    /**
     * Show verifikasi detail
     */
    public function showVerifikasi(Isian $isian)
    {
        // Semua user bisa lihat detail verifikasi
        
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