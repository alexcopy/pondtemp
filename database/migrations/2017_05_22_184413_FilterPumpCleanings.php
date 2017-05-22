<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FilterPumpCleaning extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filter_pump_cleanings', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTimeTz('cleaningDate');
            $table->string('description');
            $table->integer('pumpid'); //foreign key to devices
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
        Schema::dropIfExists('filter_pump_cleanings');
    }
}
