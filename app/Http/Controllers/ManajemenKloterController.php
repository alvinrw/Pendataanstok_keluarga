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
     * PERBAIKAN: Menambahkan kembali fungsi create() untuk mencegah error.
     * Fungsi ini tidak akan digunakan jika Anda menggunakan modal, tapi harus ada
     * untuk memenuhi rute yang mungkin masih ada.
     */
    public function create()
    {
        // Anda bisa mengarahkannya ke halaman utama jika tidak sengaja diakses
        return redirect()->route('manajemen.kloter.index');
    }

    /**
     * Menyimpan kloter baru yang dibuat dari form pop-up.
     */
  public function store(Request $request)
    {
        // 1. Validasi diubah: harga_beli_doc sekarang 'required'
        $validated = $request->validate([
            'nama_kloter' => 'required|string|max:255|unique:kloters,nama_kloter',
            'tanggal_mulai' => 'required|date',
            'jumlah_doc' => 'required|integer|min:1',
            'harga_beli_doc' => 'required|integer|min:0', // Diubah dari nullable
        ]);

        // Gunakan transaksi untuk memastikan kedua data (kloter & pengeluaran) berhasil disimpan
        DB::beginTransaction();
        try {
            // 2. Tambahkan nilai default untuk kolom stok
            $validated['stok_awal'] = 0;
            $validated['stok_tersedia'] = 0;
            
            // 3. Buat kloter baru
            $kloter = Kloter::create($validated);

            // 4. Jika ada harga beli, otomatis catat sebagai pengeluaran pertama
            if ($validated['harga_beli_doc'] > 0) {
                $kloter->pengeluarans()->create([
                    'kategori' => 'Lainnya',
                    'jumlah_pengeluaran' => $validated['harga_beli_doc'],
                    'tanggal_pengeluaran' => $validated['tanggal_mulai'],
                    'catatan' => 'Biaya pembelian DOC awal'
                ]);
            }

            DB::commit(); // Konfirmasi semua perubahan jika berhasil

            return redirect()->route('manajemen.kloter.index')
                             ->with('success', 'Kloter baru berhasil dibuat & biaya DOC dicatat sebagai pengeluaran.');

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua jika ada error
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
     * Mengubah status kloter (Aktif / Selesai Panen).
     */
    public function updateStatus(Request $request, Kloter $kloter)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['Aktif', 'Selesai Panen'])]
        ]);
        $kloter->update(['status' => $validated['status']]);
        return redirect()->route('manajemen.kloter.index')->with('success', 'Status kloter berhasil diubah.');
    }

    /**
     * Mengubah jumlah DOC awal.
     */
    public function updateDoc(Request $request, Kloter $kloter)
    {
        $validated = $request->validate(['jumlah_doc' => 'required|integer|min:0']);
        $kloter->update(['jumlah_doc' => $validated['jumlah_doc']]);
        return redirect()->route('manajemen.kloter.index')->with('success', 'Jumlah DOC berhasil diperbarui.');
    }
    
    /**
     * Mengubah tanggal mulai kloter.
     */
    public function updateTanggalMulai(Request $request, Kloter $kloter)
    {
        $validated = $request->validate([
            'tanggal_mulai' => 'required|date'
        ]);
        $kloter->update(['tanggal_mulai' => $validated['tanggal_mulai']]);
        return redirect()->route('manajemen.kloter.index')->with('success', 'Tanggal mulai berhasil diperbarui.');
    }

    /**
     * Menghapus data pengeluaran spesifik.
     */
    public function destroyPengeluaran(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();
        return redirect()->back()->with('success', 'Data pengeluaran berhasil dihapus.');
    }

    /**
     * Menghapus data kematian spesifik.
     */
    public function destroyKematian(KematianAyam $kematianAyam)
    {
        $kematianAyam->delete();
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
        return redirect()->back()->with('success', 'Data pengeluaran berhasil disimpan.');
    }

    /**
     * Menyimpan data kematian baru.
     */
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

    /**
     * Memproses konfirmasi panen (akan kita buat nanti).
     */
   
 public function konfirmasiPanen(Request $request, Kloter $kloter)
    {
        // Hitung sisa ayam untuk dijadikan stok
        $totalMati = $kloter->kematianAyams()->sum('jumlah_mati');
        $sisaAyamHidup = $kloter->jumlah_doc - $totalMati;

        $validated = $request->validate([
            'harga_jual_total' => 'required|integer|min:0',
            'tanggal_panen' => 'required|date',
        ]);

        // PERBAIKAN DI SINI:
        // Update data pada kloter, termasuk mengisi stok_tersedia
        $kloter->update([
            'status' => 'Selesai Panen',
            'harga_jual_total' => $validated['harga_jual_total'],
            'tanggal_panen' => $validated['tanggal_panen'],
            'stok_tersedia' => $sisaAyamHidup, // Sisa ayam menjadi stok siap jual
        ]);

        return redirect()->back()->with('success', 'Panen berhasil dikonfirmasi! Stok siap jual telah diperbarui.');
    }
}
