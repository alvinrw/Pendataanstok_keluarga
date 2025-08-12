<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPenjualan;
use App\Models\Summary;
use App\Models\Kloter; // <-- PENTING: Import model Kloter
use Illuminate\Support\Facades\DB; // <-- PENTING: Untuk transaksi database

class DataPenjualanController extends Controller
{
    /**
     * Menampilkan form untuk membuat data penjualan baru.
     * Ini adalah satu-satunya fungsi yang menampilkan view input.
     */
public function create()
{
    // Ambil semua data kloter
    $kloters = Kloter::orderBy('nama_kloter', 'asc')->get();

    // HITUNG TOTAL STOK GABUNGAN DARI SEMUA KLOTER
    $grandTotalStock = $kloters->sum('stok_tersedia');

    // Kirim kedua data tersebut ke view
    return view('penjualan_ayam', compact('kloters', 'grandTotalStock'));
}

    /**
     * Menyimpan data penjualan baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input, termasuk kloter_id
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_pembeli' => 'required|string|max:255',
            'jumlah_ayam_dibeli' => 'required|integer|min:1',
            'harga_asli' => 'required|integer',
            'harga_total' => 'required|integer',
            'berat_total' => 'required|integer',
            'diskon' => 'required|boolean',
            'kloter_id' => 'required|exists:kloters,id', // <-- Validasi kloter_id
        ]);

        // Gunakan transaksi database untuk memastikan konsistensi data
        DB::beginTransaction();
        try {
            // 1. Cari kloter yang dipilih
            $kloter = Kloter::findOrFail($validated['kloter_id']);

            // 2. Validasi stok di sisi server (keamanan tambahan)
            if ($kloter->stok_tersedia < $validated['jumlah_ayam_dibeli']) {
                // Jika stok tidak cukup, batalkan transaksi dan kembalikan error
                DB::rollBack();
                return back()->withInput()->withErrors(['jumlah_ayam_dibeli' => 'Stok untuk kloter ' . $kloter->nama_kloter . ' tidak mencukupi!']);
            }

            // 3. Simpan data penjualan ke tabel `data_penjualans`
            // Tambahkan kloter_id ke data yang akan disimpan
            $validated['kloter_id'] = $kloter->id;
            DataPenjualan::create($validated);

            // 4. Update statistik pada kloter yang dipilih
            $kloter->stok_tersedia -= $validated['jumlah_ayam_dibeli'];
            $kloter->total_terjual += $validated['jumlah_ayam_dibeli'];
            $kloter->total_berat += $validated['berat_total'] / 1000; // Asumsi berat dalam gram, ubah ke Kg
            $kloter->total_pemasukan += $validated['harga_total'];
            $kloter->save();

            // 5. Update tabel ringkasan utama
            $this->updateOverallSummary();

            // Jika semua berhasil, konfirmasi transaksi
            DB::commit();

            return redirect()->route('penjualan.create')->with('success', 'Data penjualan berhasil disimpan!');

        } catch (\Exception $e) {
            // Jika terjadi error, batalkan semua perubahan
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data penjualan dan mengembalikan stok.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $penjualan = DataPenjualan::findOrFail($id);
            $kloter = Kloter::find($penjualan->kloter_id);

            // Jika kloter terkait masih ada
            if ($kloter) {
                // Kembalikan stok dan kurangi statistik
                $kloter->stok_tersedia += $penjualan->jumlah_ayam_dibeli;
                $kloter->total_terjual -= $penjualan->jumlah_ayam_dibeli;
                $kloter->total_berat -= $penjualan->berat_total / 1000;
                $kloter->total_pemasukan -= $penjualan->harga_total;
                $kloter->save();
            }
            
            // Hapus data penjualan
            $penjualan->delete();

            // Update ringkasan utama
            $this->updateOverallSummary();

            DB::commit();
            return redirect()->route('penjualan.rekapan')->with('success', 'Data berhasil dihapus dan stok telah dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman rekapan penjualan.
     */
    public function rekapan()
    {
        // Mengambil data dengan relasi kloter untuk ditampilkan
        $data = DataPenjualan::with('kloter')->latest()->get();

        // Hitung summary dari data yang ada
        $summary = (object) [
            'total_ayam_terjual' => $data->sum('jumlah_ayam_dibeli'),
            'total_berat_tertimbang' => $data->sum('berat_total'),
            'total_pemasukan' => $data->sum('harga_total'),
        ];

        return view('RekapanPenjualanAyam', compact('data', 'summary'));
    }


    /**
     * Fungsi private untuk mengupdate tabel ringkasan utama.
     * Fungsi ini menghitung ulang total dari tabel 'kloters'.
     */
    private function updateOverallSummary()
    {
        // Hitung semua total dari tabel kloters
        $total_stok_tersedia = Kloter::sum('stok_tersedia');
        $total_ayam_terjual = Kloter::sum('total_terjual');
        $total_berat_tertimbang = Kloter::sum('total_berat');
        $total_pemasukan = Kloter::sum('total_pemasukan');

        // Update atau buat baris ringkasan (asumsi id=1)
        Summary::updateOrCreate(
            ['id' => 1],
            [
                'stok_ayam' => $total_stok_tersedia,
                'total_ayam_terjual' => $total_ayam_terjual,
                'total_berat_tertimbang' => $total_berat_tertimbang,
                'total_pemasukan' => $total_pemasukan,
            ]
        );
    }
}