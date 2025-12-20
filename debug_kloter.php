<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 Checking kloter with -1.200.000 profit...\n\n";

// Find kloters with total_pengeluaran around 1.2 million
$kloters = DB::table('kloters')
    ->where('total_pengeluaran', '>=', 1000000)
    ->where('total_pengeluaran', '<=', 1300000)
    ->get();

foreach ($kloters as $kloter) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "Kloter: {$kloter->nama_kloter} (ID: {$kloter->id})\n";
    echo "  Harga Beli DOC: Rp " . number_format($kloter->harga_beli_doc, 0, ',', '.') . "\n";
    echo "  Total Pengeluaran (column): Rp " . number_format($kloter->total_pengeluaran, 0, ',', '.') . "\n\n";

    // Get all expenses for this kloter
    $expenses = DB::table('pengeluarans')
        ->where('kloter_id', $kloter->id)
        ->get();

    echo "  Pengeluaran Details:\n";
    $total = 0;
    foreach ($expenses as $exp) {
        echo "    - {$exp->kategori}: Rp " . number_format($exp->jumlah_pengeluaran, 0, ',', '.') . " ({$exp->catatan})\n";
        $total += $exp->jumlah_pengeluaran;
    }
    echo "  Total (calculated): Rp " . number_format($total, 0, ',', '.') . "\n\n";

    // Fix: Update total_pengeluaran to match actual sum
    DB::table('kloters')
        ->where('id', $kloter->id)
        ->update(['total_pengeluaran' => $total]);

    echo "  ✅ Updated total_pengeluaran to Rp " . number_format($total, 0, ',', '.') . "\n";
}

echo "\n✅ Done! Refresh the page.\n";
