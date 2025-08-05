<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalsTable extends Migration
{
    public function up()
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->string('activity_name');
            $table->date('date');
            $table->time('start_time');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->enum('priority', ['low', 'medium', 'high']);
            $table->timestamps();
            
        });

        
    }

    public function down()
    {
        Schema::dropIfExists('jadwals');
    }
}
