<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WeatherReading extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weather_readings', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTimeTz('readingDate');
            $table->double('pond');
            $table->double('shed');
            $table->double('street');
            $table->double('shedhumid');
            $table->double('streethumid');
            $table->double('room');
            $table->double('roomhumid');
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
        Schema::dropIfExists('weather_readings');
    }
}
