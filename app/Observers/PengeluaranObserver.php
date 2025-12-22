<?php

namespace App\Observers;

use App\Models\Pengeluaran;
use Illuminate\Support\Facades\DB;

class PengeluaranObserver
{
    public function created(Pengeluaran $pengeluaran): void
    {
        $this->updateKloterSummary($pengeluaran->kloter_id);
    }

    public function updated(Pengeluaran $pengeluaran): void
    {
        if ($pengeluaran->isDirty(['jumlah_pengeluaran', 'kloter_id', 'deleted_at'])) {
            $this->updateKloterSummary($pengeluaran->kloter_id);

            if ($pengeluaran->isDirty('kloter_id')) {
                $this->updateKloterSummary($pengeluaran->getOriginal('kloter_id'));
            }
        }
    }

    public function deleted(Pengeluaran $pengeluaran): void
    {
        $this->updateKloterSummary($pengeluaran->kloter_id);
    }

    private function updateKloterSummary($kloterId): void
    {
        if (!$kloterId)
            return;

        $totalPengeluaran = DB::table('pengeluarans')
            ->where('kloter_id', $kloterId)
            ->whereNull('deleted_at')
            ->sum('jumlah_pengeluaran');

        DB::table('kloters')
            ->where('id', $kloterId)
            ->update(['total_pengeluaran' => $totalPengeluaran]);

        DB::table('kloter_summaries')
            ->where('kloter_id', $kloterId)
            ->update([
                'total_pengeluaran' => $totalPengeluaran,
                'last_calculated_at' => now(),
            ]);
    }
}
