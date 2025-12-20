<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔧 Updating Kloter model profit calculation...\n\n";

$modelPath = __DIR__ . '/app/Models/Kloter.php';
$content = file_get_contents($modelPath);

// Replace the profit calculation method
$oldMethod = <<<'PHP'
    public function getKeuntunganBersihAttribute()
    {
        $totalPemasukan = $this->total_pemasukan;
        $modalDoc = $this->modal_doc;
        // Hitung pengeluaran dari relasi, bukan dari column total_pengeluaran
        $totalPengeluaran = $this->pengeluarans()->sum('jumlah_pengeluaran');
        
        return $totalPemasukan - ($modalDoc + $totalPengeluaran);
    }
PHP;

$newMethod = <<<'PHP'
    public function getKeuntunganBersihAttribute()
    {
        return $this->total_pemasukan - $this->total_pengeluaran;
    }
PHP;

$content = str_replace($oldMethod, $newMethod, $content);
file_put_contents($modelPath, $content);

echo "✅ Profit calculation updated!\n";
echo "   Now using: total_pemasukan - total_pengeluaran\n";
echo "   (Modal DOC is included in total_pengeluaran)\n";
