<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataPenjualan;
use Illuminate\Support\Facades\DB;

class DataPenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Import data dari CSV file dengan Observer aktif untuk auto-update kloter
     */
    public function run(): void
    {
        $csvFile = database_path('seeders/data/data_penjualans.csv');

        if (!file_exists($csvFile)) {
            $this->command->error("CSV file not found: {$csvFile}");
            return;
        }

        $this->command->info("Importing data from: {$csvFile}");

        DB::beginTransaction();
        try {
            $file = fopen($csvFile, 'r');
            $header = fgetcsv($file); // Skip header row

            $count = 0;
            $skipped = 0;

            while (($row = fgetcsv($file)) !== false) {
                try {
                    // Create data penjualan
                    // Observer akan otomatis update kloter stock!
                    DataPenjualan::create([
                        'id' => $row[0],
                        'kloter_id' => $row[1],
                        'tanggal' => $row[2],
                        'nama_pembeli' => $row[3],
                        'jumlah_ayam_dibeli' => $row[4],
                        'berat_total' => $row[5],
                        'harga_asli' => $row[6],
                        'diskon' => $row[7],
                        'harga_total' => $row[8],
                        'created_at' => $row[9],
                        'updated_at' => $row[10],
                    ]);

                    $count++;

                    if ($count % 10 == 0) {
                        $this->command->info("  ✓ Imported {$count} records...");
                    }

                } catch (\Exception $e) {
                    $this->command->warn("  ⚠ Skipped row {$count}: {$e->getMessage()}");
                    $skipped++;
                }
            }

            fclose($file);
            DB::commit();

            $this->command->info("Successfully imported {$count} records!");
            if ($skipped > 0) {
                $this->command->warn("Skipped {$skipped} records (duplicates or errors)");
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("Error: " . $e->getMessage());
        }
    }
}
