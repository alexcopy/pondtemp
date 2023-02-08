<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_avers', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('meter_id');  //foreign key to meters table
            $table->char('value_type',10); // like ampers volts watts
            $table->float('avg_value');
            $table->tinyInteger('inverter_status');
            $table->longText('serialized');
            $table->unsignedInteger('timestamp');
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
        Schema::dropIfExists('daily_avers');
    }
};
