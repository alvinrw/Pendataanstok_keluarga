<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenjualanAyamController;
use App\Http\Controllers\RekapanAyam;
use App\Http\Controllers\SummaryAyam;
use App\Http\Controllers\SummaryController;





use App\Http\Controllers\DataPenjualanController;
use App\Http\Controllers\indexController;


use App\Http\Controllers\PenjualanTokoController;

Route::get('/PenjualanToko/input', [PenjualanTokoController::class, 'create'])->name('penjualanToko.form');
Route::post('/PenjualanToko/store', [PenjualanTokoController::class, 'store'])->name('penjualanToko.store');
Route::get('/penjualan/{id}/edit', [PenjualanTokoController::class, 'edit'])->name('penjualanToko.edit');
Route::put('/penjualan-toko/{id}', [PenjualanTokoController::class, 'update'])->name('penjualanToko.update');
Route::delete('/penjualan-toko/{id}', [PenjualanTokoController::class, 'destroy'])->name('penjualanToko.destroy');
Route::get('/penjualan-toko', [PenjualanTokoController::class, 'index'])->name('penjualanToko.index');


Route::get('/penjualan', [DataPenjualanController::class, 'index'])->name('penjualan.index');


Route::get('/RekapanPenjualanToko', [PenjualanTokoController::class, 'rekapan'])->name('penjualanToko.rekapan');


// Buat route baru untuk form input
Route::get('/penjualan/form', [DataPenjualanController::class, 'create'])->name('penjualan.form');

// Route untuk menyimpan data
Route::post('/penjualan/store', [DataPenjualanController::class, 'store'])->name('penjualan.store');



Route::get('/RekapanPenjualanAyam', [DataPenjualanController::class, 'rekapan'])->name('penjualan.rekapan');



Route::get('/penjualan/create', [DataPenjualanController::class, 'create'])->name('penjualan.create');
Route::post('/penjualan', [DataPenjualanController::class, 'store'])->name('penjualan.store');
Route::get('/',[indexController::class,'index'])->name('index.index');

//summaries
Route::get('/SummaryAyam', [SummaryController::class, 'index']);
Route::get('/summary', [SummaryController::class, 'index'])->name('summary.index');
Route::post('/update-stok', [SummaryController::class, 'updateStok'])->name('stok.update');