<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WaterMeterReadings extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('water_meter_readings', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTimeTz('readingDate');
            $table->double('readings');
            $table->integer('timestamp')->default(time());
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('water_meter_readings');
    }
}
