<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pond_weather', function (Blueprint $table) {
            $table->id();
            $table->char('town', 30);
            $table->tinyInteger('temperature');
            $table->tinyInteger('feels_like');
            $table->unsignedTinyInteger('wind_speed');
            $table->unsignedTinyInteger('visibility');
            $table->unsignedTinyInteger('uv_index');
            $table->unsignedTinyInteger('humidity');
            $table->float('precipitation');
            $table->float('pressure');
            $table->char('type', 50);
            $table->char('wind_direction', 20);
            $table->char('description', 50);
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
        Schema::dropIfExists('pond_weather');
    }
};


