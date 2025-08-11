<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kloter;
use App\Models\DataPenjualan;

class KloterController extends Controller
{
    /**
     * Menyediakan data SEMUA kloter untuk dropdown.
     */
    public function index()
    {
        // Eager load semua relasi yang dibutuhkan
        $kloters = Kloter::with(['pengeluarans', 'kematianAyams', 'dataPenjualans'])->get();

        $kloters->each(function ($kloter) {
            // 1. Ambil data penjualan dari relasi
            $kloter->total_terjual = $kloter->dataPenjualans->sum('jumlah_ayam_dibeli');
            $kloter->total_pemasukan = $kloter->dataPenjualans->sum('harga_total');
            
            // 2. Hitung keuntungan (Pemasukan - Pengeluaran yang sudah tersimpan di DB)
            $kloter->keuntungan = $kloter->total_pemasukan - $kloter->total_pengeluaran;

            // 3. PERBAIKAN DI SINI: Hitung jumlah kematian langsung dari relasinya
            $kloter->jumlah_kematian = $kloter->kematianAyams->sum('jumlah_mati');
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
