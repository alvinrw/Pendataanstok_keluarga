<?php

namespace App\Observers;

use App\Models\KematianAyam;
use Illuminate\Support\Facades\DB;

class KematianAyamObserver
{
    public function created(KematianAyam $kematian): void
    {
        $this->updateKloterSummary($kematian->kloter_id);
    }

    public function updated(KematianAyam $kematian): void
    {
        if ($kematian->isDirty(['jumlah_mati', 'kloter_id', 'deleted_at'])) {
            $this->updateKloterSummary($kematian->kloter_id);

            if ($kematian->isDirty('kloter_id')) {
                $this->updateKloterSummary($kematian->getOriginal('kloter_id'));
            }
        }
    }

    public function deleted(KematianAyam $kematian): void
    {
        $this->updateKloterSummary($kematian->kloter_id);
    }

    private function updateKloterSummary($kloterId): void
    {
        if (!$kloterId)
            return;

        $kloter = DB::table('kloters')->where('id', $kloterId)->first();
        if (!$kloter)
            return;

        $totalMati = DB::table('kematian_ayams')
            ->where('kloter_id', $kloterId)
            ->whereNull('deleted_at')
            ->sum('jumlah_mati');

        $totalPanen = DB::table('panens')
            ->where('kloter_id', $kloterId)
            ->whereNull('deleted_at')
            ->sum('jumlah_panen');

        $sisaAyamHidup = $kloter->jumlah_doc - $totalMati - $totalPanen;

        DB::table('kloters')
            ->where('id', $kloterId)
            ->update(['sisa_ayam_hidup' => $sisaAyamHidup]);

        DB::table('kloter_summaries')
            ->where('kloter_id', $kloterId)
            ->update([
                'total_mati' => $totalMati,
                'last_calculated_at' => now(),
            ]);
    }
}
