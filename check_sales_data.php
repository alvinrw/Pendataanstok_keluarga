<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 Checking data_penjualans table structure and data...\n\n";

// Get table columns
$columns = Schema::getColumnListing('data_penjualans');
echo "📋 Columns in data_penjualans:\n";
foreach ($columns as $col) {
    echo "   - {$col}\n";
}

echo "\n";

// Check if kloter_id column exists
if (in_array('kloter_id', $columns)) {
    echo "✅ kloter_id column EXISTS\n\n";

    // Count sales with and without kloter_id
    $total = DB::table('data_penjualans')->count();
    $withKloter = DB::table('data_penjualans')->whereNotNull('kloter_id')->count();
    $withoutKloter = DB::table('data_penjualans')->whereNull('kloter_id')->count();

    echo "📊 Sales Statistics:\n";
    echo "   Total sales: {$total}\n";
    echo "   With kloter_id: {$withKloter}\n";
    echo "   Without kloter_id: {$withoutKloter}\n\n";

    // Show sample data
    echo "📝 Sample sales data (first 5):\n";
    $samples = DB::table('data_penjualans')->limit(5)->get();
    foreach ($samples as $sale) {
        echo "   ID: {$sale->id} | Kloter ID: " . ($sale->kloter_id ?? 'NULL') . " | Pembeli: {$sale->nama_pembeli} | Total: Rp " . number_format($sale->total_harga, 0, ',', '.') . "\n";
    }

    echo "\n";

    // Check kloter 453 specifically
    echo "🔎 Checking Kloter 453:\n";
    $kloter453 = DB::table('kloters')->where('id', 453)->first();
    if ($kloter453) {
        echo "   Kloter Name: {$kloter453->nama_kloter}\n";

        $salesFor453 = DB::table('data_penjualans')->where('kloter_id', 453)->count();
        $totalRevenue = DB::table('data_penjualans')->where('kloter_id', 453)->sum('total_harga');

        echo "   Sales count: {$salesFor453}\n";
        echo "   Total revenue: Rp " . number_format($totalRevenue, 0, ',', '.') . "\n";
    } else {
        echo "   ❌ Kloter 453 not found!\n";
    }

} else {
    echo "❌ kloter_id column DOES NOT EXIST\n";
    echo "   You need to run the migration first!\n";
}
