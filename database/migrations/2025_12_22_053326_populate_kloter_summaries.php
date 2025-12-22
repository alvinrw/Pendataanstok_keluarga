<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     * Populate kloter_summaries from existing data
     */
    public function up(): void
    {
        $kloters = DB::table('kloters')->get();

        foreach ($kloters as $kloter) {
            // Calculate from existing data
            $totalTerjual = DB::table('data_penjualans')
                ->where('kloter_id', $kloter->id)
                ->whereNull('deleted_at')
                ->sum('jumlah_ayam_dibeli');

            $totalBerat = DB::table('data_penjualans')
                ->where('kloter_id', $kloter->id)
                ->whereNull('deleted_at')
                ->sum('berat_total') / 1000; // Convert gram to kg

            $totalPemasukan = DB::table('data_penjualans')
                ->where('kloter_id', $kloter->id)
                ->whereNull('deleted_at')
                ->sum('harga_total');

            $totalPengeluaran = DB::table('pengeluarans')
                ->where('kloter_id', $kloter->id)
                ->whereNull('deleted_at')
                ->sum('jumlah_pengeluaran');

            $totalMati = DB::table('kematian_ayams')
                ->where('kloter_id', $kloter->id)
                ->whereNull('deleted_at')
                ->sum('jumlah_mati');

            $totalPanen = DB::table('panens')
                ->where('kloter_id', $kloter->id)
                ->whereNull('deleted_at')
                ->sum('jumlah_panen');

            $stokTersedia = $kloter->jumlah_doc - $totalTerjual - $totalMati - $totalPanen;

            // Insert summary
            DB::table('kloter_summaries')->insert([
                'kloter_id' => $kloter->id,
                'total_terjual' => $totalTerjual,
                'total_berat_kg' => $totalBerat,
                'total_pemasukan' => $totalPemasukan,
                'total_pengeluaran' => $totalPengeluaran,
                'total_mati' => $totalMati,
                'total_panen' => $totalPanen,
                'stok_tersedia' => $stokTersedia,
                'last_calculated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('kloter_summaries')->truncate();
    }
};
