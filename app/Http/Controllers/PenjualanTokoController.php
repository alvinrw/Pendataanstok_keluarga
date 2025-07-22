<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenjualanToko;

class PenjualanTokoController extends Controller
{
    // ... (metode create dan store Anda sudah cukup baik)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'total_harga' => 'required|numeric', // Cukup numeric karena JS sudah mengirim angka
            'catatan' => 'nullable|string'
        ]);

        $created = PenjualanToko::create($validated);

        // Selalu kembalikan JSON untuk AJAX
        return response()->json([
            'message' => 'Data berhasil disimpan',
            'data' => $created
        ]);
    }
    
    // ... (metode edit tidak perlu diubah, sudah benar)

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'total_harga' => 'required|numeric',
            'catatan' => 'nullable|string',
        ]);

        $data = PenjualanToko::findOrFail($id);
        $data->update($validated);

        // GANTI redirect() DENGAN response()->json()
        return response()->json(['message' => 'Data berhasil diupdate.']);
    }

    public function destroy($id)
    {
        $data = PenjualanToko::findOrFail($id);
        $data->delete();

        // GANTI redirect() DENGAN response()->json()
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }

    public function rekapan()
    {
        $dataToko = PenjualanToko::orderBy('tanggal', 'desc')->get();
        return view('RekapanToko', compact('dataToko'));
    }
}