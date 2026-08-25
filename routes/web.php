<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\TransaksiKasController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============================================
// ROUTE UNTUK TAMU (BELUM LOGIN)
// ============================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

// ============================================
// ROUTE UNTUK USER YANG SUDAH LOGIN
// ============================================
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard (semua role)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ============================================
    // ROUTE KHUSUS ADMIN & BENDAHARA
    // ============================================
    Route::middleware('role:admin,bendahara')->group(function () {

        // Modul Santri
        Route::resource('santri', SantriController::class);

        // Modul Transaksi Kas
        Route::resource('transaksi', TransaksiKasController::class);

        // ============================================
        // ROUTE UANG JAJAN SANTRI (SALDO & SHORTCUT)
        // ============================================
        Route::prefix('transaksi/uang-jajan')->name('uang-jajan.')->group(function () {
            // Daftar saldo santri
            Route::get('/saldo', [TransaksiKasController::class, 'saldoSantri'])->name('saldo');

            // Form uang jajan umum (tanpa santri terpilih)
            Route::get('/create', [TransaksiKasController::class, 'formUangJajan'])->name('create');

            // Detail transaksi uang jajan santri
            Route::get('/santri/{santri}', [TransaksiKasController::class, 'detailSantri'])->name('detail');

            // Tambah saldo
            Route::get('/santri/{santri}/tambah', [TransaksiKasController::class, 'formTambahSaldo'])->name('tambah');
            Route::post('/santri/{santri}/tambah', [TransaksiKasController::class, 'storeTambahSaldo'])->name('tambah.store');

            // Tarik uang jajan
            Route::get('/santri/{santri}/tarik', [TransaksiKasController::class, 'formTarikUang'])->name('tarik');
            Route::post('/santri/{santri}/tarik', [TransaksiKasController::class, 'storeTarikUang'])->name('tarik.store');
        });
    });

    // ============================================
    // ROUTE KHUSUS ADMIN (placeholder)
    // ============================================
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // ...
    });

    // ============================================
    // ROUTE KHUSUS PIMPINAN (placeholder)
    // ============================================
    Route::middleware('role:pimpinan')->prefix('pimpinan')->name('pimpinan.')->group(function () {
        // ...
    });

    // ============================================
    // ROUTE KHUSUS ORANG TUA (placeholder)
    // ============================================
    Route::middleware('role:ortu')->prefix('ortu')->name('ortu.')->group(function () {
        // ...
    });
});

// ============================================
// REDIRECT HALAMAN UTAMA
// ============================================
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');