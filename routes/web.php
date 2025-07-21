<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenjualanAyamController;
use App\Http\Controllers\RekapanAyam;
use App\Http\Controllers\SummaryAyam;
use App\Http\Controllers\SummaryAyamController;





use App\Http\Controllers\DataPenjualanController;
use App\Http\Controllers\indexController;



Route::get('/penjualan', [DataPenjualanController::class, 'index'])->name('penjualan.index');


// Buat route baru untuk form input
Route::get('/penjualan/form', [DataPenjualanController::class, 'create'])->name('penjualan.form');

// Route untuk menyimpan data
Route::post('/penjualan/store', [DataPenjualanController::class, 'store'])->name('penjualan.store');



Route::get('/RekapanPenjualanAyam', [DataPenjualanController::class, 'rekapan'])->name('penjualan.rekapan');

Route::delete('/penjualan/{id}', [DataPenjualanController::class, 'destroy'])->name('penjualan.destroy');


Route::get('/penjualan/create', [DataPenjualanController::class, 'create'])->name('penjualan.create');
Route::post('/penjualan', [DataPenjualanController::class, 'store'])->name('penjualan.store');
Route::get('/',[indexController::class,'index'])->name('index.index');