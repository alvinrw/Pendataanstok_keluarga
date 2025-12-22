<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kloter;
use Illuminate\Support\Facades\DB;

class KloterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Import kloters dari CSV file
     */
    public function run(): void
    {
        $csvFile = database_path('seeders/data/kloters.csv');

        if (!file_exists($csvFile)) {
            $this->command->error("❌ CSV file not found: {$csvFile}");
            return;
        }

        $this->command->info("📂 Importing kloters from: {$csvFile}");

        DB::beginTransaction();
        try {
            $file = fopen($csvFile, 'r');
            $header = fgetcsv($file); // Skip header

            $count = 0;

            while (($row = fgetcsv($file)) !== false) {
                Kloter::create([
                    'id' => $row[0],
                    'nama_kloter' => $row[1],
                    'status' => $row[2],
                    'tanggal_mulai' => $row[3] ?: null,
                    'jumlah_doc' => $row[4],
                    'sisa_ayam_hidup' => $row[5],
                    'harga_beli_doc' => $row[6],
                    'total_pengeluaran' => $row[7],
                    'stok_awal' => $row[8],
                    'stok_tersedia' => $row[9],
                    'total_terjual' => $row[10],
                    'total_berat' => $row[11],
                    'total_pemasukan' => $row[12],
                    'created_at' => $row[13],
                    'updated_at' => $row[14],
                    'tanggal_panen' => $row[15] ?: null,
                    'harga_jual_total' => $row[16] ?: null,
                ]);

                $count++;
            }

            fclose($file);
            DB::commit();

            $this->command->info("✅ Successfully imported {$count} kloters!");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("❌ Error: " . $e->getMessage());
        }
    }
}
