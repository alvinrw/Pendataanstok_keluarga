<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$columns = Schema::getColumnListing('data_penjualans');
echo "Columns in data_penjualans: " . implode(', ', $columns) . "\n";

// Check if kloter_id exists
if (in_array('kloter_id', $columns)) {
    echo "kloter_id exists.\n";
} else {
    echo "kloter_id MISSING.\n";
}
