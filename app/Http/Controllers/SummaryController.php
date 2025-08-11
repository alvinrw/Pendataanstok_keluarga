<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kloter;

class SummaryController extends Controller
{
    /**
     * Menyediakan data ringkasan TOTAL dari SEMUA kloter yang sudah pernah panen.
     */
    public function index()
    {
        // Ambil semua kloter yang memiliki setidaknya satu catatan panen.
        // Kita juga muat relasi yang dibutuhkan untuk perhitungan.
        $klotersPanen = Kloter::whereHas('panens')->with(['dataPenjualans', 'pengeluarans'])->get();

        // Hitung total dari koleksi kloter yang sudah difilter
        $totalKloterPanen = $klotersPanen->count();
        $totalStokTersedia = $klotersPanen->sum('stok_tersedia');
        
        $totalAyamTerjual = 0;
        $totalPemasukan = 0;
        $totalPengeluaran = $klotersPanen->sum('total_pengeluaran');

        // Kita perlu loop untuk menjumlahkan data dari relasi
        foreach ($klotersPanen as $kloter) {
            $totalAyamTerjual += $kloter->dataPenjualans->sum('jumlah_ayam_dibeli');
            $totalPemasukan += $kloter->dataPenjualans->sum('harga_total');
        }

        $totalKeuntungan = $totalPemasukan - $totalPengeluaran;

        // Siapkan data untuk dikirim sebagai JSON
        $summaryData = [
            'total_kloter' => $totalKloterPanen,
            'stok_ayam' => $totalStokTersedia,
            'total_ayam_terjual' => $totalAyamTerjual,
            'total_keuntungan' => $totalKeuntungan,
        ];

        return response()->json($summaryData);
    }
}
