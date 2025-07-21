<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('summaries', function (Blueprint $table) {
            $table->integer('total_ayam_terjual')->default(0);
            $table->integer('total_berat_tertimbang')->default(0);
            $table->integer('total_pemasukan')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('summaries', function (Blueprint $table) {
            $table->dropColumn(['total_ayam_terjual', 'total_berat_tertimbang', 'total_pemasukan']);
        });
    }
};
