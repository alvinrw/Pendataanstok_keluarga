<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class TransaksiController extends Controller
{
 public function index(Request $request)
    {
        $query = Transaksi::query();

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            // ... (logika filter Anda)
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        // 💡 1. Pastikan baris ini ada untuk MENGHITUNG total
        $totalPengeluaran = $query->clone()->sum('jumlah');

        $transaksis = $query->latest('tanggal')->get();

        // 💡 2. Pastikan 'totalPengeluaran' ada di dalam array ini untuk DIKIRIM ke view
        return view('RekapanTransaksi', [
            'transaksis' => $transaksis,
            'totalPengeluaran' => $totalPengeluaran, // <-- INI YANG PALING PENTING
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|string',
            'jumlah' => 'required|integer',
            'deskripsi' => 'nullable|string',
        ]);

        Transaksi::create($request->all());

        return back()->with('success', 'Data berhasil disimpan!');
    }

public function rekapan(Request $request)
{
    $query = Transaksi::query();

    if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
        $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
    }

    $totalPengeluaran = $query->clone()->sum('jumlah');
    $transaksis = $query->latest('tanggal')->get();

    return view('RekapanTransaksi', [
        'transaksis' => $transaksis,
        'totalPengeluaran' => $totalPengeluaran,
    ]);
}


public function edit($id)
{
    $transaksi = Transaksi::findOrFail($id);
    return view('EditTransaksi', compact('transaksi'));
}

public function update(Request $request, $id)
{
    $transaksi = Transaksi::findOrFail($id);
    $transaksi->update($request->all());

    return response()->json(['success' => true]);
}

public function destroy($id)
{
    $transaksi = Transaksi::findOrFail($id);
    $transaksi->delete();

    return response()->json(['success' => true]);
}






}
