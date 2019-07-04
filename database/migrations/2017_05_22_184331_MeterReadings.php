<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MeterReadings extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meter_readings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meter_id');
            $table->double('readings');
            $table->string('message');
            $table->integer('timestamp')->default(time());
            $table->tinyInteger('is_disabled')->default(0);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meter_readings');
    }
}
