<?php

/**
 * Script to update existing DOC expenses from 'Lainnya' to 'DOC' category
 * Run this once to migrate existing data
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Pengeluaran;

echo "Starting migration of DOC expenses from 'Lainnya' to 'DOC' category...\n\n";

// Find all expenses with category 'Lainnya' and catatan containing 'Modal DOC'
$docExpenses = Pengeluaran::where('kategori', 'Lainnya')
    ->where('catatan', 'like', '%Modal DOC%')
    ->get();

$count = $docExpenses->count();

if ($count === 0) {
    echo "No DOC expenses found to migrate.\n";
    exit(0);
}

echo "Found {$count} DOC expense(s) to migrate:\n\n";

foreach ($docExpenses as $expense) {
    echo "- ID: {$expense->id}, Kloter: {$expense->kloter->nama_kloter}, Amount: Rp " . number_format($expense->jumlah_pengeluaran, 0, ',', '.') . "\n";
    echo "  Catatan: {$expense->catatan}\n";

    // Update the category
    $expense->kategori = 'DOC';
    $expense->save();

    echo "  ✓ Updated to 'DOC' category\n\n";
}

echo "Migration completed successfully!\n";
echo "Total expenses migrated: {$count}\n";
