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

    $created = PenjualanToko::create($validated);

    if ($request->ajax()) {
        return response()->json([
            'message' => 'Data berhasil disimpan',
            'data' => $created
        ]);
    }

    return redirect()->back()->with('success', 'Data berhasil disimpan.');
}


public function edit($id)
{
    $data = PenjualanToko::findOrFail($id);
    return response()->json($data); // agar bisa dikirim ke JS
}

public function update(Request $request, $id)
{
    $request->validate([
        'tanggal' => 'required|date',
        'total_harga' => 'required|numeric',
        'catatan' => 'nullable|string',
    ]);

    $data = PenjualanToko::findOrFail($id);
    $data->update($request->all());

    return redirect()->route('penjualanToko.index')->with('success', 'Data berhasil diupdate.');
}

public function destroy($id)
{
    $data = PenjualanToko::findOrFail($id);
    $data->delete();

    return response()->json(['message' => 'Data berhasil dihapus.'], 200);
}



public function rekapan()
{
    $dataToko = PenjualanToko::orderBy('tanggal', 'desc')->get();

    return view('RekapanToko', compact('dataToko'));
}

}
