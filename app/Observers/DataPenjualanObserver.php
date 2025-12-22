<?php

namespace App\Observers;

use App\Models\DataPenjualan;
use App\Models\KloterSummary;
use Illuminate\Support\Facades\DB;

class DataPenjualanObserver
{
    /**
     * Handle the DataPenjualan "created" event.
     * Auto-update kloter summary saat data penjualan baru dibuat
     */
    public function created(DataPenjualan $penjualan): void
    {
        $this->updateKloterSummary($penjualan->kloter_id);
    }

    /**
     * Handle the DataPenjualan "updated" event.
     * Adjust kloter summary saat data penjualan diubah
     */
    public function updated(DataPenjualan $penjualan): void
    {
        // Hanya update jika ada perubahan pada field yang mempengaruhi summary
        if ($penjualan->isDirty(['jumlah_ayam_dibeli', 'berat_total', 'harga_total', 'kloter_id', 'deleted_at'])) {
            $this->updateKloterSummary($penjualan->kloter_id);

            // Jika kloter_id berubah, update kloter lama juga
            if ($penjualan->isDirty('kloter_id')) {
                $oldKloterId = $penjualan->getOriginal('kloter_id');
                $this->updateKloterSummary($oldKloterId);
            }
        }
    }

    /**
     * Handle the DataPenjualan "deleted" event.
     * Update kloter summary saat data penjualan dihapus
     */
    public function deleted(DataPenjualan $penjualan): void
    {
        $this->updateKloterSummary($penjualan->kloter_id);
    }

    /**
     * Recalculate kloter summary from scratch
     * This ensures data is always accurate
     */
    private function updateKloterSummary($kloterId): void
    {
        if (!$kloterId) {
            return;
        }

        // Get kloter data
        $kloter = DB::table('kloters')->where('id', $kloterId)->first();
        if (!$kloter) {
            return;
        }

        // Calculate from data_penjualans (only non-deleted)
        $totalTerjual = DB::table('data_penjualans')
            ->where('kloter_id', $kloterId)
            ->whereNull('deleted_at')
            ->sum('jumlah_ayam_dibeli');

        $totalBerat = DB::table('data_penjualans')
            ->where('kloter_id', $kloterId)
            ->whereNull('deleted_at')
            ->sum('berat_total') / 1000; // Convert gram to kg

        $totalPemasukan = DB::table('data_penjualans')
            ->where('kloter_id', $kloterId)
            ->whereNull('deleted_at')
            ->sum('harga_total');

        // Calculate from pengeluarans
        $totalPengeluaran = DB::table('pengeluarans')
            ->where('kloter_id', $kloterId)
            ->whereNull('deleted_at')
            ->sum('jumlah_pengeluaran');

        // Calculate from kematian_ayams
        $totalMati = DB::table('kematian_ayams')
            ->where('kloter_id', $kloterId)
            ->whereNull('deleted_at')
            ->sum('jumlah_mati');

        // Calculate from panens
        $totalPanen = DB::table('panens')
            ->where('kloter_id', $kloterId)
            ->whereNull('deleted_at')
            ->sum('jumlah_panen');

        // Calculate stok siap jual (ayam yang sudah dipanen dan siap dijual)
        $stokTersedia = $totalPanen - $totalTerjual;

        // Calculate sisa ayam hidup (ayam yang masih di kandang)
        $sisaAyamHidup = $kloter->jumlah_doc - $totalMati - $totalPanen;

        // Update kloter sisa_ayam_hidup
        DB::table('kloters')
            ->where('id', $kloterId)
            ->update([
                'sisa_ayam_hidup' => $sisaAyamHidup,
                'total_pengeluaran' => $totalPengeluaran,
            ]);

        // Update or create summary
        DB::table('kloter_summaries')->updateOrInsert(
            ['kloter_id' => $kloterId],
            [
                'total_terjual' => $totalTerjual,
                'total_berat_kg' => $totalBerat,
                'total_pemasukan' => $totalPemasukan,
                'total_pengeluaran' => $totalPengeluaran,
                'total_mati' => $totalMati,
                'total_panen' => $totalPanen,
                'stok_tersedia' => $stokTersedia,
                'last_calculated_at' => now(),
            ]
        );
    }
}
