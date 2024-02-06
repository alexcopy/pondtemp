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
        Schema::create('water_temp_sensors', function (Blueprint $table) {
            $table->id();
            $table->decimal('temp_current', 8, 2);
            $table->char('temp_unit_convert', 5);
            $table->smallInteger('humidity_value')->default(0);
            $table->integer('bright_value')->default(0);
            $table->tinyInteger('temp_calibration')->default(0);
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
        Schema::dropIfExists('water_temp_sensors');
    }
};
