<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kloter;
use App\Models\Pengeluaran;
use App\Models\KematianAyam;
use Illuminate\Validation\Rule;

class ManajemenKloterController extends Controller
{
    // ... (fungsi index, create, store, show, destroy, detailJson biarkan sama) ...
    public function index()
    {
        $kloters = Kloter::latest()->get();
        return view('manajemen.daftar_kloter', compact('kloters'));
    }

    public function create()
    {
        return view('manajemen.create_kloter');
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'nama_kloter' => 'required|string|max:255|unique:kloters,nama_kloter',
        'tanggal_mulai' => 'required|date',
        'jumlah_doc' => 'required|integer|min:1', // Ini adalah DOC Awal
        'harga_beli_doc' => 'nullable|integer|min:0',
    ]);

    $kloter = Kloter::create($validated);

    // Langsung arahkan ke halaman detail kloter yang baru dibuat
    return redirect()->route('manajemen.kloter.show', $kloter->id)
                     ->with('success', 'Kloter baru berhasil dibuat!');
}

public function updateTanggalMulai(Request $request, Kloter $kloter)
{
    $validated = $request->validate([
        'tanggal_mulai' => 'required|date'
    ]);

    $kloter->update(['tanggal_mulai' => $validated['tanggal_mulai']]);

    return redirect()->back()->with('success', 'Tanggal mulai berhasil diperbarui.');
}

    public function show(Kloter $kloter)
    {
        $kloter->load(['pengeluarans', 'kematianAyams']);
        $totalMati = $kloter->kematianAyams->sum('jumlah_mati');
        $sisaAyam = $kloter->jumlah_doc - $totalMati;
        $totalPengeluaran = $kloter->pengeluarans->sum('jumlah_pengeluaran');
        return view('RincianAyam', compact('kloter', 'totalMati', 'sisaAyam', 'totalPengeluaran'));
    }

    public function destroy(Kloter $kloter)
    {
        $kloter->delete();
        return redirect()->route('manajemen.kloter.index')
                         ->with('success', 'Kloter berhasil dihapus.');
    }

    public function detailJson(Kloter $kloter)
    {
        $kloter->load(['pengeluarans' => fn($q) => $q->latest(), 'kematianAyams' => fn($q) => $q->latest()]);
        $totalMati = $kloter->kematianAyams->sum('jumlah_mati');
        $sisaAyam = $kloter->jumlah_doc - $totalMati;
        $totalPengeluaran = $kloter->pengeluarans->sum('jumlah_pengeluaran');
        $data = [
            'kloter' => $kloter,
            'rekapan' => ['total_mati' => $totalMati, 'sisa_ayam' => $sisaAyam, 'total_pengeluaran' => $totalPengeluaran]
        ];
        return response()->json($data);
    }

    /**
     * FUNGSI YANG HILANG ADA DI SINI.
     * Fungsi ini menerima status yang dipilih dari form.
     */
    public function updateStatus(Request $request, Kloter $kloter)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['Aktif', 'Selesai Panen'])]
        ]);

        $kloter->update(['status' => $validated['status']]);

        return redirect()->route('manajemen.kloter.index')->with('success', 'Status kloter berhasil diubah.');
    }

    public function updateDoc(Request $request, Kloter $kloter)
    {
        $validated = $request->validate(['jumlah_doc' => 'required|integer|min:0']);
        $kloter->update(['jumlah_doc' => $validated['jumlah_doc']]);
        return redirect()->back()->with('success', 'Jumlah DOC berhasil diperbarui.');
    }

    public function destroyPengeluaran(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();
        return redirect()->back()->with('success', 'Data pengeluaran berhasil dihapus.');
    }

    public function destroyKematian(KematianAyam $kematianAyam)
    {
        $kematianAyam->delete();
        return redirect()->back()->with('success', 'Data kematian berhasil dihapus.');
    }

    public function storePengeluaran(Request $request, Kloter $kloter)
    {
        $validated = $request->validate([
            'kategori' => ['required', Rule::in(['Pakan', 'Obat', 'Lainnya'])],
            'jumlah_pengeluaran' => 'required|integer|min:0',
            'tanggal_pengeluaran' => 'required|date',
        ]);
        $kloter->pengeluarans()->create($validated);
        return redirect()->back()->with('success', 'Data pengeluaran berhasil disimpan.');
    }

    public function storeKematian(Request $request, Kloter $kloter)
    {
        $totalMatiSaatIni = $kloter->kematianAyams()->sum('jumlah_mati');
        $sisaAyam = $kloter->jumlah_doc - $totalMatiSaatIni;
        $validated = $request->validate([
            'jumlah_mati' => "required|integer|min:1|max:{$sisaAyam}",
            'tanggal_kematian' => 'required|date',
            'catatan' => 'nullable|string',
        ]);
        $kloter->kematianAyams()->create($validated);
        return redirect()->back()->with('success', 'Data kematian berhasil dicatat.');
    }

    public function konfirmasiPanen(Request $request, Kloter $kloter)
    {
        $totalMati = $kloter->kematianAyams()->sum('jumlah_mati');
        $sisaAyamHidup = $kloter->jumlah_doc - $totalMati;
        $validated = $request->validate([
            'harga_jual_total' => 'required|integer|min:0',
            'tanggal_panen' => 'required|date',
        ]);
        $kloter->update([
            'status' => 'Selesai Panen',
            'harga_jual_total' => $validated['harga_jual_total'],
            'tanggal_panen' => $validated['tanggal_panen'],
            'stok_tersedia' => $sisaAyamHidup,
        ]);
        return redirect()->back()->with('success', 'Panen berhasil dikonfirmasi!');
    }
}
