<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import semua Controller di sini agar rapi
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DataPenjualanController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PenjualanTokoController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\KloterController;
use App\Http\Controllers\ManajemenKloterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =============================
// Halaman Utama & Login
// =============================
Route::get('/', fn() => view('login'));
Route::get('/welcome', fn() => view('welcome'))->name('welcome');
Route::get('/login-admin', [AdminAuthController::class, 'showLoginForm']);
Route::post('/login-admin', [AdminAuthController::class, 'login']);
Route::get('/dashboard-admin', function () {
    if (!session('admin_logged_in')) {
        return redirect('/login-admin');
    }
    return view('admin.dashboard');
});
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// =============================
// Jadwal (Kelola Jadwal)
// =============================
Route::get('/input-jadwal', [JadwalController::class, 'create'])->name('jadwal.input');
Route::post('/input-jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
Route::get('/rekapan-jadwal', [JadwalController::class, 'rekapan'])->name('jadwal.rekapan');
Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
Route::put('/jadwal/{id}/status', [JadwalController::class, 'updateStatus']);
Route::get('/jadwal/{id}/edit', [JadwalController::class, 'edit']);
Route::put('/jadwal/{id}', [JadwalController::class, 'update']);

// =============================
// Transaksi Pengeluaran
// =============================
Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
Route::get('/rekapan', [TransaksiController::class, 'rekapan'])->name('rekapan');
Route::get('/transaksi/input', fn() => view('InputTransaksi'))->name('transaksi.input');
Route::get('/transaksi/{id}/edit', [TransaksiController::class, 'edit'])->name('transaksi.edit');
Route::put('/transaksi/{id}', [TransaksiController::class, 'update']);
Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy']);

// =============================
// Kelola Akun Admin
// =============================
Route::get('/akun', [AdminController::class, 'index'])->name('akun.index');
Route::get('/kelola-akun', [AdminController::class, 'index'])->name('admin.index');
Route::put('/users/{id}', [AdminController::class, 'update'])->name('users.update');
Route::post('/users', [AdminController::class, 'store'])->name('users.store');
Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('users.destroy');

// =============================
// Penjualan Toko Ayam
// =============================
Route::get('/PenjualanToko/input', [PenjualanTokoController::class, 'create'])->name('penjualanToko.form');
Route::post('/PenjualanToko/store', [PenjualanTokoController::class, 'store'])->name('penjualanToko.store');
Route::get('/penjualan/{id}/edit', [PenjualanTokoController::class, 'edit'])->name('penjualanToko.edit');
Route::put('/penjualan-toko/{id}', [PenjualanTokoController::class, 'update'])->name('penjualanToko.update');
Route::delete('/penjualan-toko/{id}', [PenjualanTokoController::class, 'destroy'])->name('penjualanToko.destroy');
Route::get('/penjualan-toko', [PenjualanTokoController::class, 'index'])->name('penjualanToko.index');
Route::get('/RekapanPenjualanToko', [PenjualanTokoController::class, 'rekapan'])->name('penjualanToko.rekapan');

// =============================
// Penjualan Ayam Non-Toko
// =============================
Route::get('/penjualan', [DataPenjualanController::class, 'index'])->name('penjualan.index');
Route::get('/penjualan/form', [DataPenjualanController::class, 'create'])->name('penjualan.form');
Route::get('/penjualan/create', [DataPenjualanController::class, 'create'])->name('penjualan.create');
Route::post('/penjualan/store', [DataPenjualanController::class, 'store'])->name('penjualan.store');
Route::get('/RekapanPenjualanAyam', [DataPenjualanController::class, 'rekapan'])->name('penjualan.rekapan');
Route::delete('/RekapanPenjualanAyam/{id}', [DataPenjualanController::class, 'destroy'])->name('penjualan.destroy');

// =============================
// Summary Ayam (API)
// =============================
Route::get('/SummaryAyam', fn() => view('SummaryAyam'));
Route::get('/kloters', [KloterController::class, 'index']);
Route::post('/kloters', [KloterController::class, 'store']);
Route::put('/kloters/{kloter}/update-stock', [KloterController::class, 'updateStock']);
Route::get('/summaries', [SummaryController::class, 'index']);
Route::delete('/kloters/{kloter}', [KloterController::class, 'destroy']);

// ======================================================
// RUTE UNTUK FITUR MANAJEMEN PEMELIHARAAN AYAM
// ======================================================
Route::get('/manajemen-kloter', [ManajemenKloterController::class, 'index'])->name('manajemen.kloter.index');
Route::get('/manajemen-kloter/create', [ManajemenKloterController::class, 'create'])->name('manajemen.kloter.create');
Route::post('/manajemen-kloter', [ManajemenKloterController::class, 'store'])->name('manajemen.kloter.store');
Route::get('/manajemen-kloter/{kloter}', [ManajemenKloterController::class, 'show'])->name('manajemen.kloter.show');
Route::post('/manajemen-kloter/{kloter}/pengeluaran', [ManajemenKloterController::class, 'storePengeluaran'])->name('manajemen.kloter.storePengeluaran');
Route::post('/manajemen-kloter/{kloter}/kematian', [ManajemenKloterController::class, 'storeKematian'])->name('manajemen.kloter.storeKematian');
Route::post('/manajemen-kloter/{kloter}/panen', [ManajemenKloterController::class, 'konfirmasiPanen'])->name('manajemen.kloter.konfirmasiPanen');
Route::delete('/manajemen-kloter/{kloter}', [ManajemenKloterController::class, 'destroy'])->name('manajemen.kloter.destroy');
Route::get('/manajemen-kloter/{kloter}/detail-json', [ManajemenKloterController::class, 'detailJson'])->name('manajemen.kloter.detailJson');
Route::put('/manajemen-kloter/{kloter}/update-status', [ManajemenKloterController::class, 'updateStatus'])->name('manajemen.kloter.updateStatus');
Route::put('/manajemen-kloter/{kloter}/update-doc', [ManajemenKloterController::class, 'updateDoc'])->name('manajemen.kloter.updateDoc');
Route::delete('/pengeluaran/{pengeluaran}', [ManajemenKloterController::class, 'destroyPengeluaran'])->name('manajemen.pengeluaran.destroy');
Route::delete('/kematian/{kematianAyam}', [ManajemenKloterController::class, 'destroyKematian'])->name('manajemen.kematian.destroy');
Route::put('/manajemen-kloter/{kloter}/update-tanggal', [ManajemenKloterController::class, 'updateTanggalMulai'])->name('manajemen.kloter.updateTanggal');




