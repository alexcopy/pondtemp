<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('solar_powers', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('meter_id');  //foreign key to meters table
            $table->char('value_type',10); // like ampers volts watts
            $table->float('hourly_value');
            $table->tinyInteger('inverter_status');
            $table->longText('serialized');
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
        Schema::dropIfExists('solar_powers');
    }
};
