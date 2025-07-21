<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPenjualan;
use App\Models\Summary;

class DataPenjualanController extends Controller
{
    public function index()
    {
      
        $data = DataPenjualan::all();
$stokAyam = Summary::sum('stok_ayam');

return view('penjualan_ayam', compact('data', 'stokAyam'));


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

    return redirect()->back()->with('success', 'Data penjualan berhasil disimpan dan stok diperbarui!');
}
    public function destroy($id)
{
    $data = DataPenjualan::findOrFail($id);
    $data->delete();

    return redirect()->route('penjualan.index')->with('success', 'Data berhasil dihapus.');
}



    public function rekapan()
{
    $data = DataPenjualan::all(); // Ambil semua data penjualan
    return view('RekapanPenjualanAyam', compact('data'));
}

}

