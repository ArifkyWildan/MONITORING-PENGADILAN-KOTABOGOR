<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IsianController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    // ========== DASHBOARD (SEMUA ROLE BISA AKSES) ==========
    Route::get('/dashboard', [IsianController::class, 'index'])->name('dashboard');
    
    // ========== LIST ISIAN (SEMUA ROLE BISA AKSES) ==========
    Route::get('/isian/list', [IsianController::class, 'list'])->name('isian.list');
    
    // ========== ADMIN ONLY ==========
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/isian/daftar', [IsianController::class, 'daftar'])->name('isian.daftar');
        Route::get('/isian/create', [IsianController::class, 'create'])->name('isian.create');
        Route::post('/isian', [IsianController::class, 'store'])->name('isian.store');
        Route::get('/isian/{isian}/edit', [IsianController::class, 'edit'])->name('isian.edit');
        Route::put('/isian/{isian}', [IsianController::class, 'update'])->name('isian.update');
        Route::delete('/isian/{isian}', [IsianController::class, 'destroy'])->name('isian.destroy');
    });
    
    // ========== VERIFIKATOR ONLY ==========
    Route::middleware(['role:verifikator'])->group(function () {
        Route::get('/isian/verifikasi', [IsianController::class, 'verifikasi'])->name('isian.verifikasi');
        Route::post('/isian/{isian}/verifikasi', [IsianController::class, 'storeVerifikasi'])->name('isian.verifikasi.store');
    });
    
    // ========== USER ONLY (ISI LINK) ==========
    Route::middleware(['role:user'])->group(function () {
        Route::post('/isian/{isian}/link', [IsianController::class, 'storeLink'])->name('isian.link.store');
    });

    // ========== SHARED ROUTES (VERIFIKATOR & SEMUA USER) ==========
    Route::get('/isian/{isian}/verifikasi/detail', [IsianController::class, 'showVerifikasi'])->name('isian.verifikasi.show');

    // ========== PROFILE (SEMUA ROLE) ==========
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';