<?php

namespace App\Observers;

use App\Models\Panen;
use Illuminate\Support\Facades\DB;

class PanenObserver
{
    /**
     * Handle the Panen "created" event.
     */
    public function created(Panen $panen): void
    {
        $this->updateKloterSummary($panen->kloter_id);
    }

    /**
     * Handle the Panen "updated" event.
     */
    public function updated(Panen $panen): void
    {
        if ($panen->isDirty(['jumlah_panen', 'kloter_id', 'deleted_at'])) {
            $this->updateKloterSummary($panen->kloter_id);

            if ($panen->isDirty('kloter_id')) {
                $oldKloterId = $panen->getOriginal('kloter_id');
                $this->updateKloterSummary($oldKloterId);
            }
        }
    }

    /**
     * Handle the Panen "deleted" event.
     */
    public function deleted(Panen $panen): void
    {
        $this->updateKloterSummary($panen->kloter_id);
    }

    /**
     * Recalculate kloter summary
     */
    private function updateKloterSummary($kloterId): void
    {
        if (!$kloterId)
            return;

        $kloter = DB::table('kloters')->where('id', $kloterId)->first();
        if (!$kloter)
            return;

        // Calculate totals
        $totalPanen = DB::table('panens')
            ->where('kloter_id', $kloterId)
            ->whereNull('deleted_at')
            ->sum('jumlah_panen');

        $totalTerjual = DB::table('data_penjualans')
            ->where('kloter_id', $kloterId)
            ->whereNull('deleted_at')
            ->sum('jumlah_ayam_dibeli');

        $totalMati = DB::table('kematian_ayams')
            ->where('kloter_id', $kloterId)
            ->whereNull('deleted_at')
            ->sum('jumlah_mati');

        // Update kloter
        $sisaAyamHidup = $kloter->jumlah_doc - $totalMati - $totalPanen;
        DB::table('kloters')
            ->where('id', $kloterId)
            ->update(['sisa_ayam_hidup' => $sisaAyamHidup]);

        // Update summary
        DB::table('kloter_summaries')
            ->where('kloter_id', $kloterId)
            ->update([
                'total_panen' => $totalPanen,
                'stok_tersedia' => $totalPanen - $totalTerjual,
                'last_calculated_at' => now(),
            ]);
    }
}
