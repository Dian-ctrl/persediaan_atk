<?php

use Illuminate\Support\Facades\Route;

// ── Controllers ───────────────────────────────────────────
use App\Http\Controllers\AdminLoginController;

// Admin
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiMasukController;
use App\Http\Controllers\TransaksiKeluarController;
use App\Http\Controllers\StokOpnameController;
use App\Http\Controllers\LaporanController;

// Pengguna (tanpa login)
use App\Http\Controllers\Pengguna\PenggunaDashboardController;
use App\Http\Controllers\Pengguna\PenggunaBarangController;
use App\Http\Controllers\Pengguna\PenggunaTransaksiMasukController;
use App\Http\Controllers\Pengguna\PenggunaTransaksiKeluarController;

// ═══════════════════════════════════════════════════════════
// ROOT → Halaman Pilih Role (Admin / Pengguna)
// ═══════════════════════════════════════════════════════════
Route::get('/', function () {
    return view('pilih-role');
})->name('pilih.role');

// ═══════════════════════════════════════════════════════════
// ADMIN LOGIN (kredensial hardcoded, tanpa database user)
// ═══════════════════════════════════════════════════════════
Route::get('/admin/login',   [AdminLoginController::class, 'showLoginForm'])->name('login.admin');
Route::post('/admin/login',  [AdminLoginController::class, 'login'])->name('login.admin.post');
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('logout.admin');

// ═══════════════════════════════════════════════════════════
// ADMIN ROUTES (dilindungi AdminMiddleware – cek session)
// ═══════════════════════════════════════════════════════════
Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data
    Route::resource('kategori',         KategoriController::class);
    Route::resource('satuan',           SatuanController::class);
    Route::resource('barang',           BarangController::class);

    // Transaksi
    Route::resource('transaksi-masuk',  TransaksiMasukController::class);
    Route::resource('transaksi-keluar', TransaksiKeluarController::class);

    // Laporan

    // Laporan Persediaan
    Route::get('/laporan',                 [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/preview',         [LaporanController::class, 'preview'])->name('laporan.preview');
    Route::get('/laporan/download-excel',  [LaporanController::class, 'downloadExcel'])->name('laporan.download-excel');
    Route::get('/laporan/download-pdf',    [LaporanController::class, 'downloadPdf'])->name('laporan.download-pdf');
    Route::get('/laporan/pengaturan-ttd',  [LaporanController::class, 'pengaturanTtd'])->name('laporan.pengaturan-ttd');
    Route::post('/laporan/pengaturan-ttd', [LaporanController::class, 'simpanPengaturanTtd'])->name('laporan.simpan-ttd');
});

// ═══════════════════════════════════════════════════════════
// PENGGUNA ROUTES (BEBAS – tanpa login, tanpa middleware)
// ═══════════════════════════════════════════════════════════
Route::prefix('pengguna')->name('pengguna.')->group(function () {

    Route::get('/dashboard', [PenggunaDashboardController::class, 'index'])->name('dashboard');

    // Data Barang (baca saja)
    Route::get('/barang', [PenggunaBarangController::class, 'index'])->name('barang.index');

    // Barang Masuk
    Route::get('/transaksi-masuk',        [PenggunaTransaksiMasukController::class, 'index'])->name('transaksi-masuk.index');
    Route::get('/transaksi-masuk/create', [PenggunaTransaksiMasukController::class, 'create'])->name('transaksi-masuk.create');
    Route::post('/transaksi-masuk',       [PenggunaTransaksiMasukController::class, 'store'])->name('transaksi-masuk.store');

    // Barang Keluar
    Route::get('/transaksi-keluar',        [PenggunaTransaksiKeluarController::class, 'index'])->name('transaksi-keluar.index');
    Route::get('/transaksi-keluar/create', [PenggunaTransaksiKeluarController::class, 'create'])->name('transaksi-keluar.create');
    Route::post('/transaksi-keluar',       [PenggunaTransaksiKeluarController::class, 'store'])->name('transaksi-keluar.store');
});
