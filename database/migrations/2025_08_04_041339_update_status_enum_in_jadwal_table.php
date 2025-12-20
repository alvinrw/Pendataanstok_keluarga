<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // ⬅️ Tambahkan ini

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE jadwals MODIFY status ENUM('pending', 'done', 'cancel', 'ongoing') DEFAULT 'ongoing'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE jadwals MODIFY status ENUM('pending', 'done', 'cancel') DEFAULT 'pending'");
        }
    }
};
