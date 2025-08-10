<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Summary;
use App\Models\Kloter; // <-- Tambahkan ini

class SummaryController extends Controller
{
    /**
     * Menyediakan data ringkasan TOTAL dari SEMUA kloter yang sudah panen.
     */
    public function index()
    {
        // Ambil semua kloter yang statusnya 'Selesai Panen'
        $klotersPanen = Kloter::where('status', 'Selesai Panen')->get();

        // Hitung total dari koleksi
        $totalAyamTerjual = $klotersPanen->sum('total_terjual');
        $totalStokTersedia = $klotersPanen->sum('stok_tersedia');
        $totalBeratTerjual = $klotersPanen->sum('total_berat');
        $totalPemasukan = $klotersPanen->sum('total_pemasukan');

        $summaryData = [
            'total_kloter' => $klotersPanen->count(),
            'total_ayam_terjual' => $totalAyamTerjual,
            'stok_ayam' => $totalStokTersedia,
            'total_berat_tertimbang' => $totalBeratTerjual,
            'total_pemasukan' => $totalPemasukan,
        ];

        return response()->json($summaryData);
    }
}