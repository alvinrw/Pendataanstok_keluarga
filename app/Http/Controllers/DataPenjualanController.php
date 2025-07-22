<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPenjualan;
use App\Models\Summary;


class DataPenjualanController extends Controller
{
    public function index()
    {
     $data = DataPenjualan::all(); // atau DataPenjualan::all(), pilih salah satu sesuai model kamu
    $stokAyam = Summary::sum('stok_ayam');
    $summary = Summary::latest()->first();

    return view('penjualan_ayam', compact('data', 'stokAyam', 'summary'));

    }

public function updateSummary()
{
    $totalAyam = DataPenjualan::sum('jumlah_ayam_dibeli');
    $totalBerat = DataPenjualan::sum('berat_total');
    $totalPemasukan = DataPenjualan::sum('harga_total');

    // Ambil 1 baris yang pasti (id = 1 misalnya)
    $summary = Summary::find(1);

    if ($summary) {
        $summary->update([
            'total_ayam_terjual' => $totalAyam,
            'total_berat_tertimbang' => $totalBerat,
            'total_pemasukan' => $totalPemasukan,
        ]);
    } else {
        // Buat hanya 1x kalau memang belum ada
        Summary::create([
            'stok_ayam' => 0,
            'total_ayam_terjual' => $totalAyam,
            'total_berat_tertimbang' => $totalBerat,
            'total_pemasukan' => $totalPemasukan,
        ]);
    }
}




public function create()
{
    $data = DataPenjualan::all();
    $stokAyam = Summary::sum('stok_ayam');
    return view('penjualan_ayam', compact('data', 'stokAyam'));
}

public function store(Request $request)
{
    // Validasi
    $validated = $request->validate([
        'tanggal' => 'required|date',
        'nama_pembeli' => 'required|string',
        'jumlah_ayam_dibeli' => 'required|integer|min:1',
        'harga_asli' => 'required|integer',
        'harga_total' => 'required|integer',
        'berat_total' => 'required|integer',
        'diskon' => 'required|boolean'
    ]);

    // Simpan data penjualan
    DataPenjualan::create($validated);

    // Update stok ayam
    $latestSummary = Summary::latest()->first();
    if ($latestSummary) {
        $latestSummary->stok_ayam -= $validated['jumlah_ayam_dibeli'];
        $latestSummary->save();
    }

    // Update summary penjualan
    $this->updateSummary();

    return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil disimpan dan summary diperbarui!');
}

public function destroy($id)
{
    $data = DataPenjualan::findOrFail($id);

    // Ambil data jumlah ayam yang akan dihapus
    $jumlahAyam = $data->jumlah_ayam_dibeli;

    // Tambahkan kembali stok ayam
    $latestSummary = Summary::latest()->first();
    if ($latestSummary) {
        $latestSummary->stok_ayam += $jumlahAyam;
        $latestSummary->save();
    }

    // Hapus data penjualan
    $data->delete();

    // Update summary
    $this->updateSummary();

    return redirect()->route('penjualan.rekapan')->with('success', 'Data berhasil dihapus dari rekapan.');
}



public function rekapan()
{
    $data = DataPenjualan::all(); // Ambil semua data penjualan

    // Hitung summary dan kirim ke view
    $summary = (object) [
        'total_ayam_terjual' => $data->sum('jumlah_ayam_dibeli'),
        'total_berat_tertimbang' => $data->sum('berat_total'),
        'total_pemasukan' => $data->sum('harga_total'),
    ];

    return view('RekapanPenjualanAyam', compact('data', 'summary'));
}

}

