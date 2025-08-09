<?php

namespace App\Http\Controllers;

use App\Models\Kloter;
use App\Models\Summary;
use Illuminate\Http\Request;

class KloterController extends Controller
{
    // Mengambil semua data kloter
    public function index()
    {
        $kloters = Kloter::all();
        return response()->json($kloters);
    }

    // Menyimpan kloter baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_kloter' => 'required|unique:kloters,nama_kloter',
            'stok_awal' => 'required|integer|min:0',
        ]);

        $kloter = Kloter::create([
            'nama_kloter' => $request->nama_kloter,
            'stok_awal' => $request->stok_awal,
            'stok_tersedia' => $request->stok_awal,
        ]);

        $this->updateSummary();

        return response()->json($kloter, 201);
    }

    // Memperbarui stok kloter
    public function updateStock(Request $request, Kloter $kloter)
    {
        $request->validate([
            'new_stock' => 'required|integer|min:0',
        ]);

        $kloter->stok_tersedia = $request->new_stock;
        $kloter->save();

        $this->updateSummary();

        return response()->json($kloter);
    }

    // Fungsi untuk memperbarui tabel ringkasan
    private function updateSummary()
    {
        $total_kloters = Kloter::count();
        $total_ayam_terjual = Kloter::sum('total_terjual');
        $total_stok_tersedia = Kloter::sum('stok_tersedia');
        $total_berat_tertimbang = Kloter::sum('total_berat');
        $total_pemasukan = Kloter::sum('total_pemasukan');

        $summary = Summary::firstOrNew(['id' => 1]); // Asumsi hanya ada satu baris summary
        $summary->total_ayam_terjual = $total_ayam_terjual;
        $summary->stok_ayam = $total_stok_tersedia;
        $summary->total_berat_tertimbang = $total_berat_tertimbang;
        $summary->total_pemasukan = $total_pemasukan;
        $summary->save();
    }
}