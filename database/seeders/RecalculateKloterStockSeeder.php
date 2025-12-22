<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kloter;
use App\Models\DataPenjualan;
use Illuminate\Support\Facades\DB;

class RecalculateKloterStockSeeder extends Seeder
{
    /**
     * Recalculate semua kloter stock berdasarkan data penjualan yang ada
     * Ini untuk fix data yang di-import manual (bypass Observer)
     */
    public function run(): void
    {
        $this->command->info("Recalculating kloter stock from sales data...");

        DB::beginTransaction();
        try {
            $kloters = Kloter::all();

            foreach ($kloters as $kloter) {
                // Hitung dari data penjualan
                $penjualans = DataPenjualan::where('kloter_id', $kloter->id)->get();

                $total_terjual = $penjualans->sum('jumlah_ayam_dibeli');
                $total_berat = $penjualans->sum('berat_total') / 1000; // gram to kg
                $total_pemasukan = $penjualans->sum('harga_total');

                // Update kloter
                $kloter->total_terjual = $total_terjual;
                $kloter->total_berat = $total_berat;
                $kloter->total_pemasukan = $total_pemasukan;

                // Recalculate stok_tersedia
                // stok_tersedia = jumlah_doc - total_terjual - (jumlah_mati + jumlah_panen)
                $total_mati = $kloter->kematianAyams()->sum('jumlah_mati');
                $total_panen = $kloter->panens()->sum('jumlah_panen');

                $kloter->stok_tersedia = $kloter->jumlah_doc - $total_terjual - $total_mati - $total_panen;
                $kloter->sisa_ayam_hidup = $kloter->jumlah_doc - $total_mati - $total_panen;

                $kloter->save();

                $this->command->info("  [OK] Kloter {$kloter->id} ({$kloter->nama_kloter}):");
                $this->command->info("       Total terjual: {$total_terjual}");
                $this->command->info("       Total berat: {$total_berat} kg");
                $this->command->info("       Total pemasukan: Rp " . number_format($total_pemasukan));
                $this->command->info("       Stok tersedia: {$kloter->stok_tersedia}");
            }

            DB::commit();
            $this->command->info("\n[DONE] All kloter stock recalculated successfully!");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("[ERROR] " . $e->getMessage());
        }
    }
}
