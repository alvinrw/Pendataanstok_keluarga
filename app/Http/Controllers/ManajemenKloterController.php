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
     * Menyimpan kloter baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input yang lebih ketat
        $validated = $request->validate([
            'nama_kloter' => ['required', 'string', 'max:255', 'unique:kloters,nama_kloter'],
            'jumlah_doc' => ['required', 'integer', 'min:1', 'max:100000'],
            'tanggal_mulai' => ['required', 'date', 'before_or_equal:today'],
            'harga_beli_doc' => ['required', 'numeric', 'min:0', 'max:999999999'],
        ], [
            'nama_kloter.required' => 'Nama kloter wajib diisi.',
            'nama_kloter.unique' => 'Nama kloter sudah digunakan, pilih nama lain.',
            'jumlah_doc.required' => 'Jumlah DOC wajib diisi.',
            'jumlah_doc.min' => 'Jumlah DOC minimal 1 ekor.',
            'jumlah_doc.max' => 'Jumlah DOC maksimal 100.000 ekor.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.before_or_equal' => 'Tanggal mulai tidak boleh di masa depan.',
            'harga_beli_doc.required' => 'Harga beli DOC wajib diisi.',
            'harga_beli_doc.min' => 'Harga beli DOC tidak boleh negatif.',
        ]);

        DB::beginTransaction();
        try {
            // Buat kloter baru
            $kloter = Kloter::create([
                'nama_kloter' => $validated['nama_kloter'],
                'jumlah_doc' => $validated['jumlah_doc'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'sisa_ayam_hidup' => $validated['jumlah_doc'],
                'total_pengeluaran' => 0,
                'stok_tersedia' => 0,
                'stok_awal' => 0, // Tambahkan ini untuk fix NOT NULL constraint
            ]);

            // Otomatis buat pengeluaran DOC
            Pengeluaran::create([
                'kloter_id' => $kloter->id,
                'kategori' => 'DOC',
                'jumlah_pengeluaran' => $validated['harga_beli_doc'],
                'tanggal_pengeluaran' => $validated['tanggal_mulai'],
                'catatan' => 'Pembelian DOC awal',
                'jumlah_pakan_kg' => 0,
            ]);

            $this->updateKloterCalculations($kloter);

            DB::commit();
            return redirect()->route('manajemen.kloter.index')->with('success', 'Kloter berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal menambahkan kloter: ' . $e->getMessage()])->withInput();
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
        $kloter->load(['pengeluarans' => fn($q) => $q->latest(), 'kematianAyams' => fn($q) => $q->latest(), 'panens' => fn($q) => $q->latest(), 'dataPenjualans' => fn($q) => $q->latest()]);

        $data = [
            'kloter' => $kloter,
            'rekapan' => [
                'total_mati' => $kloter->kematianAyams->sum('jumlah_mati'),
                'total_panen' => $kloter->panens->sum('jumlah_panen'),
                'sisa_ayam' => $kloter->sisa_ayam_hidup,
                'total_pengeluaran' => $kloter->total_pengeluaran,
                'total_pakan_kg' => $kloter->pengeluarans->sum('jumlah_pakan_kg'),
            ],
            // BUSINESS ANALYTICS DATA
            'analytics' => [
                'modal_doc' => $kloter->modal_doc,
                'total_pemasukan' => $kloter->total_pemasukan,
                'total_berat_terjual' => $kloter->total_berat_terjual,
                'keuntungan_bersih' => $kloter->keuntungan_bersih,
                'margin_keuntungan' => $kloter->margin_keuntungan,
                'fcr' => $kloter->fcr,
                'breakdown_pengeluaran' => $kloter->breakdown_pengeluaran,
                'mortality_rate' => $kloter->mortality_rate,
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
            'kategori' => ['required', Rule::in(['Pakan', 'Obat', 'Listrik/Air', 'Tenaga Kerja', 'Pemeliharaan Kandang', 'Lainnya', 'DOC'])],
            'jumlah_pengeluaran' => 'required|integer|min:0',
            'tanggal_pengeluaran' => 'required|date',
            'catatan' => 'nullable|string|max:255',
            'jumlah_pakan_kg' => 'required_if:kategori,Pakan|nullable|numeric|min:0',
        ]);
        $kloter->pengeluarans()->create($validated);
        $this->updateKloterCalculations($kloter);
        return redirect()->back()->with('success', 'Data pengeluaran berhasil disimpan.');
    }

    /**
     * Update existing expense.
     */
    public function updatePengeluaran(Request $request, Pengeluaran $pengeluaran)
    {
        $validated = $request->validate([
            'kategori' => ['required', Rule::in(['Pakan', 'Obat', 'Listrik/Air', 'Tenaga Kerja', 'Pemeliharaan Kandang', 'Lainnya', 'DOC'])],
            'jumlah_pengeluaran' => 'required|integer|min:0',
            'tanggal_pengeluaran' => 'required|date',
            'catatan' => 'nullable|string|max:255',
            'jumlah_pakan_kg' => 'required_if:kategori,Pakan|nullable|numeric|min:0',
        ]);

        $kloter = $pengeluaran->kloter;
        $pengeluaran->update($validated);
        $this->updateKloterCalculations($kloter);
        return redirect()->back()->with('success', 'Data pengeluaran berhasil diperbarui.');
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

        $totalTerjual = $kloter->dataPenjualans()->sum('jumlah_ayam_dibeli');
        $totalPanenSaatIni = $kloter->panens()->sum('jumlah_panen');
        $totalPanenSetelahHapus = $totalPanenSaatIni - $panen->jumlah_panen;

        if ($totalPanenSetelahHapus < $totalTerjual) {
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus! Data panen ini tidak dapat dihapus karena sebagian ayamnya sudah terjual. Hapus data penjualan terkait terlebih dahulu.']);
        }

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
}
