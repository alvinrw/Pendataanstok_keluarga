<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('jadwals', function (Blueprint $table) {
        $table->enum('status', ['pending', 'done', 'cancel','ongoing'])->default('ongoing');
    });
}

public function down()
{
    Schema::table('jadwals', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}

};
