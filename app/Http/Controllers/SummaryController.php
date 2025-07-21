<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Summary;

class SummaryController extends Controller
{
    // public function index()
    // {
    //     // Ambil semua data summary dari tabel `summaries`
    //     $summaries = Summary::all();

    //     // Kirim ke view
      
    // }

public function index()
{
    $summaries = Summary::all();
    $summary = Summary::latest()->first(); // Tambahkan ini
    $stokAyam = $summary ? $summary->stok_ayam : 0;

    return view('SummaryAyam', compact('summaries', 'stokAyam'));   
}


    public function updateStok(Request $request)
    {
        $request->validate([
            'stok_ayam' => 'required|integer|min:0',
        ]);

        // Ambil entri paling baru atau buat baru jika belum ada
        $summary = Summary::latest()->first();
        if (!$summary) {
            $summary = new Summary();
        }

        $summary->stok_ayam = $request->stok_ayam;
        $summary->save();

        return response()->json([
            'success' => true,
            'stok_ayam' => $summary->stok_ayam,
        ]);
    }
}

