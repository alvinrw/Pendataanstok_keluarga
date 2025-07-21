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
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_pembeli' => 'required|string',
            'jumlah_ayam_dibeli' => 'required|integer',
            'berat_total' => 'required|numeric',
            'harga_asli' => 'required|integer',
            'diskon' => 'required|integer',
            'harga_total' => 'required|integer',
        ]);

        DataPenjualan::create($validated);

        return redirect()->route('penjualan.index')->with('success', 'Data berhasil disimpan.');
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

