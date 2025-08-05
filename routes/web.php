<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenjualanAyamController;
use App\Http\Controllers\RekapanAyam;
use App\Http\Controllers\SummaryAyam;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\DataPenjualanController;
use App\Http\Controllers\indexController;
use App\Http\Controllers\PenjualanTokoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Berikut adalah daftar route yang digunakan dalam aplikasi.
| Route dikelompokkan berdasarkan fitur untuk memudahkan pembacaan.
*/

// =============================
// Halaman Utama
// =============================
Route::get('/', function () {
    return view('login');
});

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

//login
use App\Http\Controllers\AdminAuthController;

Route::get('/login-admin', [AdminAuthController::class, 'showLoginForm']);
Route::post('/login-admin', [AdminAuthController::class, 'login']);
Route::get('/dashboard-admin', function () {
    if (!session('admin_logged_in')) {
        return redirect('/login-admin');
    }

    return view('admin.dashboard');
});



//alvin
use App\Http\Controllers\JadwalController;

Route::get('/input-jadwal', [JadwalController::class, 'create'])->name('jadwal.input');
Route::post('/input-jadwal', [JadwalController::class, 'store'])->name('jadwal.store');


//rekapan jadwal
Route::get('/rekapan-jadwal', [JadwalController::class, 'rekapan'])->name('jadwal.rekapan');

// Route untuk delete jadwal (DELETE)
Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');

// Route untuk update status jadwal (PUT)
Route::put('/jadwal/{id}/status', [JadwalController::class, 'updateStatus']);

// Route untuk edit jadwal (GET & PUT)
Route::get('/jadwal/{id}/edit', [JadwalController::class, 'edit']);
Route::put('/jadwal/{id}', [JadwalController::class, 'update']);


//pengeluaran
use App\Http\Controllers\TransaksiController;

Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
Route::get('/rekapan', [TransaksiController::class, 'rekapan'])->name('rekapan');
Route::get('/transaksi/input', function () {
    return view('InputTransaksi'); // ganti dengan nama file view kamu (misalnya InputTransaksi.blade.php)
})->name('transaksi.input');

// Tampilkan form edit
Route::get('/transaksi/{id}/edit', [TransaksiController::class, 'edit'])->name('transaksi.edit');

// Proses update setelah form disubmit
Route::put('/transaksi/{id}', [TransaksiController::class, 'update']);

Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy']);

use App\Http\Controllers\AdminController;
Route::get('/akun', [AdminController::class, 'index'])->name('akun.index');
Route::get('/kelola-akun', [AdminController::class, 'index'])->name('admin.index');
Route::put('/users/{id}', [AdminController::class, 'update'])->name('users.update');
Route::post('/users', [AdminController::class, 'store'])->name('users.store');
Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('users.destroy');


//logout

use Illuminate\Support\Facades\Auth;

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/'); // Atau redirect ke halaman login
})->name('logout');





// =============================
// Penjualan Toko Ayam
// =============================

// Form input penjualan toko
Route::get('/PenjualanToko/input', [PenjualanTokoController::class, 'create'])->name('penjualanToko.form');

// Simpan data penjualan toko
Route::post('/PenjualanToko/store', [PenjualanTokoController::class, 'store'])->name('penjualanToko.store');

// Edit data penjualan toko
Route::get('/penjualan/{id}/edit', [PenjualanTokoController::class, 'edit'])->name('penjualanToko.edit');

// Update data penjualan toko
Route::put('/penjualan-toko/{id}', [PenjualanTokoController::class, 'update'])->name('penjualanToko.update');

// Hapus data penjualan toko
Route::delete('/penjualan-toko/{id}', [PenjualanTokoController::class, 'destroy'])->name('penjualanToko.destroy');

// Tampilkan data penjualan toko
Route::get('/penjualan-toko', [PenjualanTokoController::class, 'index'])->name('penjualanToko.index');

// Rekapan penjualan toko
Route::get('/RekapanPenjualanToko', [PenjualanTokoController::class, 'rekapan'])->name('penjualanToko.rekapan');


// =============================
// Penjualan Ayam Biasa (Non-Toko)
// =============================

// Tampilkan semua data penjualan
Route::get('/penjualan', [DataPenjualanController::class, 'index'])->name('penjualan.index');

// Form input penjualan
Route::get('/penjualan/form', [DataPenjualanController::class, 'create'])->name('penjualan.form');
Route::get('/penjualan/create', [DataPenjualanController::class, 'create'])->name('penjualan.create'); // sama dengan atas

// Simpan data penjualan
Route::post('/penjualan/store', [DataPenjualanController::class, 'store'])->name('penjualan.store');
Route::post('/penjualan', [DataPenjualanController::class, 'store'])->name('penjualan.store'); // sama dengan atas

// Rekapan penjualan
Route::get('/RekapanPenjualanAyam', [DataPenjualanController::class, 'rekapan'])->name('penjualan.rekapan');

// Hapus data rekapan
Route::delete('/RekapanPenjualanAyam/{id}', [DataPenjualanController::class, 'destroy'])->name('penjualan.destroy');


// =============================
// Summary Ayam (Stok dan Ringkasan)
// =============================

// Tampilkan ringkasan stok ayam
Route::get('/SummaryAyam', [SummaryController::class, 'index']);
Route::get('/summary', [SummaryController::class, 'index'])->name('summary.index');

// Update stok ayam
Route::post('/update-stok', [SummaryController::class, 'updateStok'])->name('stok.update');
