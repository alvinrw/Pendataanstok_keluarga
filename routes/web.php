<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// =============================
// Controller
// =============================
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DataPenjualanController;
use App\Http\Controllers\indexController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PenjualanAyamController;
use App\Http\Controllers\PenjualanTokoController;
use App\Http\Controllers\RekapanAyam;
use App\Http\Controllers\SummaryAyam;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\TransaksiController;

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

// Logout
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
Route::get('/penjualan/create', [DataPenjualanController::class, 'create'])->name('penjualan.create'); // duplikat
Route::post('/penjualan/store', [DataPenjualanController::class, 'store'])->name('penjualan.store');
Route::post('/penjualan', [DataPenjualanController::class, 'store'])->name('penjualan.store'); // duplikat
Route::get('/RekapanPenjualanAyam', [DataPenjualanController::class, 'rekapan'])->name('penjualan.rekapan');
Route::delete('/RekapanPenjualanAyam/{id}', [DataPenjualanController::class, 'destroy'])->name('penjualan.destroy');

// =============================
// Summary Ayam
// =============================
Route::get('/SummaryAyam', [SummaryController::class, 'index']); // tanpa nama



use App\Http\Controllers\KloterController;

// Rute untuk semua API dashboard kloter
Route::get('/kloters', [KloterController::class, 'index']);
Route::post('/kloters', [KloterController::class, 'store']);
Route::put('/kloters/{kloter}/update-stock', [KloterController::class, 'updateStock']);
Route::get('/summaries', [KloterController::class, 'summary']);