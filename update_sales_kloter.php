<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔄 Updating kloter_id for existing sales data...\n\n";

// Get all sales records that don't have kloter_id
$salesWithoutKloter = DB::table('data_penjualans')
    ->whereNull('kloter_id')
    ->get();

echo "Found " . $salesWithoutKloter->count() . " sales records without kloter_id\n\n";

if ($salesWithoutKloter->count() === 0) {
    echo "✅ All sales records already have kloter_id!\n";
    exit(0);
}

$updated = 0;
$failed = 0;

foreach ($salesWithoutKloter as $sale) {
    // Try to find the kloter by name from the sale record
    // Assuming there's a 'nama_kloter' or similar field in data_penjualans
    // If not, we'll need to match differently

    // First, let's check if there's a kloter field in the sale
    $kloterName = $sale->kloter ?? null;

    if ($kloterName) {
        // Find the kloter by name
        $kloter = DB::table('kloters')
            ->where('nama_kloter', $kloterName)
            ->first();

        if ($kloter) {
            // Update the sale with kloter_id
            DB::table('data_penjualans')
                ->where('id', $sale->id)
                ->update(['kloter_id' => $kloter->id]);

            $updated++;
            echo "✅ Updated sale ID {$sale->id} → Kloter: {$kloter->nama_kloter} (ID: {$kloter->id})\n";
        } else {
            $failed++;
            echo "❌ Sale ID {$sale->id}: Kloter '{$kloterName}' not found\n";
        }
    } else {
        $failed++;
        echo "⚠️  Sale ID {$sale->id}: No kloter name found in record\n";
    }
}

echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 Summary:\n";
echo "   ✅ Updated: {$updated}\n";
echo "   ❌ Failed: {$failed}\n";
echo "   📝 Total: " . $salesWithoutKloter->count() . "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

if ($updated > 0) {
    echo "\n🎉 Success! Sales data has been linked to kloters.\n";
    echo "Now refresh the 'Kelola Pemeliharaan' page to see updated analytics!\n";
}
