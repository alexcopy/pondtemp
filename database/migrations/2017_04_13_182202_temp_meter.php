<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TempMeter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_meter', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTimeTz('readingDate');
            $table->double('tempVal');
            $table->string('location');
            $table->integer('timestamp');
            $table->integer('userId');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_meter');

    }
}
