<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixNegativeStockSeeder extends Seeder
{
    /**
     * Auto-create panen records for kloters with negative stock
     * This fixes old data where penjualan was created without panen
     */
    public function run(): void
    {
        $this->command->info("Fixing negative stock by creating panen records...");

        DB::beginTransaction();
        try {
            // Get all kloters with negative stok_tersedia
            $kloters = DB::table('kloter_summaries')
                ->where('stok_tersedia', '<', 0)
                ->get();

            foreach ($kloters as $summary) {
                $kloter = DB::table('kloters')->where('id', $summary->kloter_id)->first();

                if (!$kloter) {
                    continue;
                }

                // Calculate how much panen is needed
                $totalTerjual = $summary->total_terjual;
                $totalPanenExisting = $summary->total_panen;
                $panenNeeded = $totalTerjual - $totalPanenExisting;

                if ($panenNeeded > 0) {
                    // Create panen record
                    DB::table('panens')->insert([
                        'kloter_id' => $kloter->id,
                        'jumlah_panen' => $panenNeeded,
                        'tanggal_panen' => $kloter->tanggal_mulai, // Use kloter start date
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $this->command->info("  [OK] Created panen {$panenNeeded} ekor for Kloter {$kloter->id} ({$kloter->nama_kloter})");

                    // Update summary
                    DB::table('kloter_summaries')
                        ->where('kloter_id', $kloter->id)
                        ->update([
                            'total_panen' => $totalPanenExisting + $panenNeeded,
                            'stok_tersedia' => 0, // Now balanced
                            'last_calculated_at' => now(),
                        ]);
                }
            }

            DB::commit();
            $this->command->info("\n[DONE] All negative stocks fixed!");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("[ERROR] " . $e->getMessage());
        }
    }
}
