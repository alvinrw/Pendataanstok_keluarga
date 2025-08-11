<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kloter;
use App\Models\DataPenjualan;

class KloterController extends Controller
{
    /**
     * Menyediakan data SEMUA kloter untuk dropdown.
     * Perhitungan sekarang lebih sederhana karena membaca data yang sudah disimpan.
     */
    public function index()
    {
        // Hanya perlu mengambil relasi data penjualan untuk menghitung pemasukan
        $kloters = Kloter::with('dataPenjualans')->get();

        $kloters->each(function ($kloter) {
            // 1. Ambil data penjualan dari relasi
            $kloter->total_terjual = $kloter->dataPenjualans->sum('jumlah_ayam_dibeli');
            $kloter->total_pemasukan = $kloter->dataPenjualans->sum('harga_total');
            $kloter->total_berat = $kloter->dataPenjualans->sum('berat_total') / 1000; // Ubah ke Kg
            
            // 2. Hitung keuntungan (Pemasukan - Pengeluaran yang sudah tersimpan di DB)
            $kloter->keuntungan = $kloter->total_pemasukan - $kloter->total_pengeluaran;

            // 3. Hitung jumlah kematian (DOC Awal - Sisa Ayam yang sudah tersimpan di DB)
            $kloter->jumlah_kematian = $kloter->jumlah_doc - $kloter->sisa_ayam_hidup;
        });

        return response()->json($kloters);
    }

    /**
     * Memperbarui stok untuk kloter yang dipilih.
     */
    public function updateStock(Request $request, Kloter $kloter)
    {
        $validated = $request->validate([
            'new_stock' => 'required|integer|min:0',
        ]);

        $kloter->update(['stok_tersedia' => $validated['new_stock']]);
        
        return response()->json($kloter);
    }
}
