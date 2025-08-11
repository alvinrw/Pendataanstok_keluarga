<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kloter;
use App\Models\Pengeluaran;
use App\Models\KematianAyam;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ManajemenKloterController extends Controller
{
    /**
     * Menampilkan halaman utama (daftar semua kloter).
     */
    public function index()
    {
        $kloters = Kloter::latest()->get();
        return view('manajemen.daftar_kloter', compact('kloters'));
    }

    /**
     * Menampilkan form untuk membuat kloter baru (tidak digunakan jika memakai modal,
     * tapi disimpan untuk mencegah error rute).
     */
    public function create()
    {
        return redirect()->route('manajemen.kloter.index');
    }

    /**
     * FUNGSI UTAMA: Ini adalah "kalkulator" pribadi untuk setiap kloter.
     * Akan kita panggil setiap kali ada perubahan data.
     */
    private function updateKloterCalculations(Kloter $kloter)
    {
        // Hitung ulang semua dari awal agar data selalu akurat
        $totalMati = $kloter->kematianAyams()->sum('jumlah_mati');
        $totalPengeluaran = $kloter->pengeluarans()->sum('jumlah_pengeluaran');
        $sisaAyam = $kloter->jumlah_doc - $totalMati;

        // Simpan hasil perhitungan ke database
        $kloter->sisa_ayam_hidup = $sisaAyam;
        $kloter->total_pengeluaran = $totalPengeluaran;

        // LOGIKA SINKRONISASI:
        // Jika kloter sudah panen, update juga stok tersedianya.
        if ($kloter->status === 'Selesai Panen') {
            $kloter->stok_tersedia = $sisaAyam;
        }

        $kloter->save();
    }

    /**
     * Menyimpan kloter baru yang dibuat dari form pop-up.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kloter' => 'required|string|max:255|unique:kloters,nama_kloter',
            'tanggal_mulai' => 'required|date',
            'jumlah_doc' => 'required|integer|min:1',
            'harga_beli_doc' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Inisialisasi data saat kloter dibuat
            $validated['stok_awal'] = 0;
            $validated['stok_tersedia'] = 0;
            $validated['sisa_ayam_hidup'] = $validated['jumlah_doc']; // Sisa ayam = jumlah awal
            $validated['total_pengeluaran'] = $validated['harga_beli_doc']; // Pengeluaran awal = harga DOC

            $kloter = Kloter::create($validated);

            if ($validated['harga_beli_doc'] > 0) {
                $kloter->pengeluarans()->create([
                    'kategori' => 'Lainnya',
                    'jumlah_pengeluaran' => $validated['harga_beli_doc'],
                    'tanggal_pengeluaran' => $validated['tanggal_mulai'],
                    'catatan' => 'Biaya pembelian DOC awal'
                ]);
            }

            DB::commit();
            return redirect()->route('manajemen.kloter.index')
                             ->with('success', 'Kloter baru berhasil dibuat & biaya DOC dicatat sebagai pengeluaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal menyimpan kloter. Silakan coba lagi.']);
        }
    }

    /**
     * Menghapus kloter dari database.
     */
    public function destroy(Kloter $kloter)
    {
        $kloter->delete();
        return redirect()->route('manajemen.kloter.index')->with('success', 'Kloter berhasil dihapus.');
    }
    
    /**
     * Menyediakan data detail kloter dalam format JSON untuk modal AJAX.
     */
    public function detailJson(Kloter $kloter)
    {
        // Sekarang kita tidak perlu menghitung lagi, cukup ambil data yang sudah tersimpan
        $kloter->load(['pengeluarans' => fn($q) => $q->latest(), 'kematianAyams' => fn($q) => $q->latest()]);
        
        $data = [
            'kloter' => $kloter,
            'rekapan' => [
                'total_mati' => $kloter->jumlah_doc - $kloter->sisa_ayam_hidup,
                'sisa_ayam' => $kloter->sisa_ayam_hidup,
                'total_pengeluaran' => $kloter->total_pengeluaran
            ]
        ];
        
        return response()->json($data);
    }
    
    /**
     * Mengubah status kloter (Aktif / Selesai Panen).
     */
    public function updateStatus(Request $request, Kloter $kloter)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['Aktif', 'Selesai Panen'])]
        ]);
        
        $newStatus = $validated['status'];
        $updateData = ['status' => $newStatus];

        if ($newStatus === 'Selesai Panen') {
            $totalMati = $kloter->kematianAyams()->sum('jumlah_mati');
            $sisaAyamHidup = $kloter->jumlah_doc - $totalMati;
            $updateData['stok_tersedia'] = $sisaAyamHidup;
        } else {
            $updateData['stok_tersedia'] = 0;
            $updateData['tanggal_panen'] = null;
            $updateData['harga_jual_total'] = null;
        }

        $kloter->update($updateData);
        return redirect()->route('manajemen.kloter.index')->with('success', 'Status kloter berhasil diubah.');
    }

    /**
     * Mengubah jumlah DOC awal.
     */
    public function updateDoc(Request $request, Kloter $kloter)
    {
        $validated = $request->validate(['jumlah_doc' => 'required|integer|min:0']);
        $kloter->update(['jumlah_doc' => $validated['jumlah_doc']]);
        
        $this->updateKloterCalculations($kloter);

        return redirect()->route('manajemen.kloter.index')->with('success', 'Jumlah DOC berhasil diperbarui.');
    }
    
    /**
     * Mengubah tanggal mulai kloter.
     */
    public function updateTanggalMulai(Request $request, Kloter $kloter)
    {
        $validated = $request->validate(['tanggal_mulai' => 'required|date']);
        $kloter->update(['tanggal_mulai' => $validated['tanggal_mulai']]);
        return redirect()->route('manajemen.kloter.index')->with('success', 'Tanggal mulai berhasil diperbarui.');
    }

    /**
     * Menghapus data pengeluaran spesifik.
     */
    public function destroyPengeluaran(Pengeluaran $pengeluaran)
    {
        $kloter = $pengeluaran->kloter;
        $pengeluaran->delete();
        $this->updateKloterCalculations($kloter);
        return redirect()->back()->with('success', 'Data pengeluaran berhasil dihapus.');
    }

    /**
     * Menghapus data kematian spesifik.
     */
    public function destroyKematian(KematianAyam $kematianAyam)
    {
        $kloter = $kematianAyam->kloter;
        $kematianAyam->delete();
        $this->updateKloterCalculations($kloter);
        return redirect()->back()->with('success', 'Data kematian berhasil dihapus.');
    }

    /**
     * Menyimpan data pengeluaran baru.
     */
    public function storePengeluaran(Request $request, Kloter $kloter)
    {
        $validated = $request->validate([
            'kategori' => ['required', Rule::in(['Pakan', 'Obat', 'Lainnya'])],
            'jumlah_pengeluaran' => 'required|integer|min:0',
            'tanggal_pengeluaran' => 'required|date',
        ]);
        $kloter->pengeluarans()->create($validated);
        $this->updateKloterCalculations($kloter);
        return redirect()->back()->with('success', 'Data pengeluaran berhasil disimpan.');
    }

    /**
     * Menyimpan data kematian baru.
     */
    public function storeKematian(Request $request, Kloter $kloter)
    {
        $sisaAyam = $kloter->sisa_ayam_hidup;
        $validated = $request->validate([
            'jumlah_mati' => "required|integer|min:1|max:{$sisaAyam}",
            'tanggal_kematian' => 'required|date',
            'catatan' => 'nullable|string',
        ]);
        $kloter->kematianAyams()->create($validated);
        $this->updateKloterCalculations($kloter);
        return redirect()->back()->with('success', 'Data kematian berhasil dicatat.');
    }

    /**
     * Memproses konfirmasi panen dari form di dalam detail.
     */
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

    public function koreksiStok(Request $request, Kloter $kloter)
    {
        $validated = $request->validate([
            'sisa_ayam_hidup' => 'required|integer|min:0'
        ]);

        $sisaAyamBaru = $validated['sisa_ayam_hidup'];
        $totalMati = $kloter->kematianAyams()->sum('jumlah_mati');

        // Hitung mundur untuk menemukan DOC awal yang seharusnya
        $docAwalBaru = $sisaAyamBaru + $totalMati;

        // Update kedua nilai di database
        $kloter->jumlah_doc = $docAwalBaru;
        $kloter->sisa_ayam_hidup = $sisaAyamBaru;
        $kloter->save();

        // Panggil kalkulator utama untuk memastikan semua data lain sinkron
        $this->updateKloterCalculations($kloter);

        return redirect()->route('manajemen.kloter.index')->with('success', 'Jumlah ayam berhasil dikoreksi dan DOC awal telah disesuaikan.');
    }
}
