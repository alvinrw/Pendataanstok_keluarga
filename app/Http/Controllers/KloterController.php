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
        $kloters = Kloter::with(['pengeluarans', 'kematianAyams', 'dataPenjualans'])->get();

        $kloters->each(function ($kloter) {
            $kloter->total_terjual = $kloter->dataPenjualans->sum('jumlah_ayam_dibeli');
            $kloter->total_pemasukan = $kloter->dataPenjualans->sum('harga_total');
            
            // PERUBAHAN DI SINI: Kita tambahkan properti baru untuk total pengeluaran
            $totalPengeluaran = $kloter->pengeluarans->sum('jumlah_pengeluaran');
            $kloter->total_pengeluaran = $totalPengeluaran;

            $keuntungan = $kloter->total_pemasukan - $totalPengeluaran;
            $kloter->keuntungan = $keuntungan;

            $totalMati = $kloter->kematianAyams->sum('jumlah_mati');
            $persentaseKematian = ($kloter->jumlah_doc > 0) ? ($totalMati / $kloter->jumlah_doc) * 100 : 0;
            $kloter->persentase_kematian = round($persentaseKematian, 2);
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
