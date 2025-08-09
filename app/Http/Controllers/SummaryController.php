<?php

namespace App\Http\Controllers;

use App\Models\Summary; // <-- Pastikan ini di-import
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    /**
     * Mengambil dan menampilkan data ringkasan total sebagai JSON.
     */
    public function index()
    {
        // Mengambil data ringkasan pertama dari tabel.
        // Jika tabel kosong (misal saat aplikasi pertama kali jalan),
        // buat baris baru dengan nilai default 0.
        $summary = Summary::firstOrCreate(
            ['id' => 1], // Kunci untuk mencari data ringkasan
            [           // Nilai default jika tidak ditemukan
                'total_ayam_terjual' => 0,
                'stok_ayam' => 0,
                'total_berat_tertimbang' => 0,
                'total_pemasukan' => 0,
            ]
        );

        // Mengembalikan data sebagai respons JSON
        return response()->json($summary);
    }
}