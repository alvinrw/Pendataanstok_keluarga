<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kloter;
use App\Models\Pengeluaran;
use App\Models\KematianAyam;
use App\Models\Panen;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ManajemenKloterController extends Controller
{
    /**
     * "Kalkulator Utama" yang menghitung semua data kloter.
     * Fungsi ini akan dipanggil setiap kali ada perubahan data.
     */
    private function updateKloterCalculations(Kloter $kloter)
    {
        // Muat ulang relasi untuk mendapatkan data terbaru
        $kloter->load(['kematianAyams', 'panens', 'pengeluarans', 'dataPenjualans']);

        $totalMati = $kloter->kematianAyams->sum('jumlah_mati');
        $totalPanen = $kloter->panens->sum('jumlah_panen');
        $totalPengeluaran = $kloter->pengeluarans->sum('jumlah_pengeluaran');
        $totalTerjual = $kloter->dataPenjualans->sum('jumlah_ayam_dibeli');
        
        // Rumus sisa ayam di kandang
        $sisaAyamDiKandang = $kloter->jumlah_doc - $totalMati - $totalPanen;

        // Rumus stok yang siap dijual di halaman summary
        $stokSiapJual = $totalPanen - $totalTerjual;

        // Simpan semua hasil perhitungan ke database
        $kloter->sisa_ayam_hidup = $sisaAyamDiKandang;
        $kloter->total_pengeluaran = $totalPengeluaran;
        $kloter->stok_tersedia = $stokSiapJual;

        $kloter->save();
    }

    /**
     * Menampilkan halaman utama (daftar semua kloter).
     */
public function index()
{
    // Eager load semua relasi yang dibutuhkan
    $kloters = Kloter::with(['kematianAyams', 'dataPenjualans'])->get();

    $kloters->each(function ($kloter) {
        $kloter->total_terjual = $kloter->dataPenjualans->sum('jumlah_ayam_dibeli');
        $kloter->total_pemasukan = $kloter->dataPenjualans->sum('harga_total');
        $kloter->keuntungan = $kloter->total_pemasukan - $kloter->total_pengeluaran;
        $kloter->jumlah_kematian = $kloter->kematianAyams->sum('jumlah_mati');
    });

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
            $validated['stok_awal'] = 0;
            $validated['stok_tersedia'] = 0;
            $validated['sisa_ayam_hidup'] = $validated['jumlah_doc'];
            $validated['total_pengeluaran'] = $validated['harga_beli_doc'];
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
        $kloter->load(['pengeluarans' => fn($q) => $q->latest(), 'kematianAyams' => fn($q) => $q->latest(), 'panens' => fn($q) => $q->latest()]);
        
        $data = [
            'kloter' => $kloter,
            'rekapan' => [
                'total_mati' => $kloter->kematianAyams->sum('jumlah_mati'),
                'total_panen' => $kloter->panens->sum('jumlah_panen'),
                'sisa_ayam' => $kloter->sisa_ayam_hidup,
                'total_pengeluaran' => $kloter->total_pengeluaran
            ]
        ];
        
        return response()->json($data);
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
     * Menyimpan data panen parsial.
     */
    public function storePanen(Request $request, Kloter $kloter)
    {
        $sisaAyam = $kloter->sisa_ayam_hidup;
        $validated = $request->validate([
            'jumlah_panen' => "required|integer|min:1|max:{$sisaAyam}",
            'tanggal_panen' => 'required|date',
        ]);

        $kloter->panens()->create($validated);
        $this->updateKloterCalculations($kloter);

        return redirect()->back()->with('success', 'Data panen berhasil dicatat.');
    }

    /**
     * Menghapus data panen.
     */
    public function destroyPanen(Panen $panen)
    {
        $kloter = $panen->kloter;
        $panen->delete();
        $this->updateKloterCalculations($kloter);
        return redirect()->back()->with('success', 'Data panen berhasil dihapus.');
    }
    
    /**
     * Mengoreksi stok akhir dan menyesuaikan DOC awal.
     */
    public function koreksiStok(Request $request, Kloter $kloter)
    {
        $validated = $request->validate(['sisa_ayam_hidup' => 'required|integer|min:0']);
        $sisaAyamBaru = $validated['sisa_ayam_hidup'];
        $totalMati = $kloter->kematianAyams()->sum('jumlah_mati');
        $totalPanen = $kloter->panens()->sum('jumlah_panen');
        $docAwalBaru = $sisaAyamBaru + $totalMati + $totalPanen;
        $kloter->jumlah_doc = $docAwalBaru;
        $kloter->save();
        $this->updateKloterCalculations($kloter);
        return redirect()->route('manajemen.kloter.index')->with('success', 'Jumlah ayam berhasil dikoreksi.');
    }

     public function updateStock(Request $request, Kloter $kloter)
    {
        $validated = $request->validate([
            'new_stock' => 'required|integer|min:0',
        ]);

        $kloter->update(['stok_tersedia' => $validated['new_stock']]);
        
        return response()->json($kloter);
    }


}
