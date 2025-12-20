<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🧹 Cleaning up duplicate DOC expenses...\n\n";

// Get all kloters
$kloters = DB::table('kloters')->get();

$cleaned = 0;

foreach ($kloters as $kloter) {
    // Find all "Lainnya" expenses for this kloter that match the harga_beli_doc
    $docExpenses = DB::table('pengeluarans')
        ->where('kloter_id', $kloter->id)
        ->where('kategori', 'Lainnya')
        ->where('jumlah_pengeluaran', $kloter->harga_beli_doc)
        ->get();

    if ($docExpenses->count() > 1) {
        echo "Kloter '{$kloter->nama_kloter}' has {$docExpenses->count()} DOC expenses\n";

        // Keep only the most recent one, delete the rest
        $toKeep = $docExpenses->sortByDesc('created_at')->first();
        $toDelete = $docExpenses->where('id', '!=', $toKeep->id);

        foreach ($toDelete as $expense) {
            DB::table('pengeluarans')->where('id', $expense->id)->delete();
            echo "  ✅ Deleted duplicate expense ID {$expense->id}\n";
            $cleaned++;
        }
    } elseif ($docExpenses->count() == 0) {
        // No DOC expense, create one
        echo "Kloter '{$kloter->nama_kloter}' has no DOC expense, creating...\n";
        DB::table('pengeluarans')->insert([
            'kloter_id' => $kloter->id,
            'kategori' => 'Lainnya',
            'jumlah_pengeluaran' => $kloter->harga_beli_doc,
            'tanggal_pengeluaran' => $kloter->tanggal_mulai,
            'catatan' => 'Modal DOC (Pembelian ' . $kloter->jumlah_doc . ' ekor)',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "  ✅ Created DOC expense\n";
        $cleaned++;
    }

    // Update total_pengeluaran
    $totalPengeluaran = DB::table('pengeluarans')
        ->where('kloter_id', $kloter->id)
        ->sum('jumlah_pengeluaran');

    DB::table('kloters')
        ->where('id', $kloter->id)
        ->update(['total_pengeluaran' => $totalPengeluaran]);
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ Cleanup complete!\n";
echo "   Total changes: {$cleaned}\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "\nRefresh the page to see the updated data!\n";
