<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenjualanToko;

class PenjualanTokoController extends Controller
{
       public function create()
    {
        return view('InputDataToko');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'tanggal' => 'required|date',
        'total_harga' => 'required|string',
        'catatan' => 'nullable|string'
    ]);

    // parsing "Rp ..." ke int
    $validated['total_harga'] = (int) preg_replace('/[^\d]/', '', $validated['total_harga']);

    PenjualanToko::create($validated);

    return redirect()->back()->with('success', 'Data berhasil disimpan.');
}

public function rekapan()
{
    $dataToko = PenjualanToko::orderBy('tanggal', 'desc')->get();

    return view('rekapan_penjualan_toko', compact('dataToko'));
}

}
