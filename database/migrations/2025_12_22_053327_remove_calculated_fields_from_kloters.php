<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     * Remove calculated fields from kloters table
     */
    public function up(): void
    {
        Schema::table('kloters', function (Blueprint $table) {
            $table->dropColumn([
                'stok_awal',
                'stok_tersedia',
                'total_terjual',
                'total_berat',
                'total_pemasukan',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     * Re-add columns and populate from summaries
     */
    public function down(): void
    {
        Schema::table('kloters', function (Blueprint $table) {
            $table->integer('stok_awal')->default(0)->after('harga_beli_doc');
            $table->integer('stok_tersedia')->default(0)->after('stok_awal');
            $table->integer('total_terjual')->default(0)->after('stok_tersedia');
            $table->decimal('total_berat', 10, 2)->default(0)->after('total_terjual');
            $table->decimal('total_pemasukan', 15, 2)->default(0)->after('total_berat');
        });

        // Repopulate from summaries
        $summaries = DB::table('kloter_summaries')->get();
        foreach ($summaries as $summary) {
            DB::table('kloters')
                ->where('id', $summary->kloter_id)
                ->update([
                        'total_terjual' => $summary->total_terjual,
                        'stok_tersedia' => $summary->stok_tersedia,
                        'total_berat' => $summary->total_berat_kg,
                        'total_pemasukan' => $summary->total_pemasukan,
                    ]);
        }
    }
};
