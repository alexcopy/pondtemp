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
        Schema::create('solar_meters', function (Blueprint $table) {
            $table->increments('id');
            $table->char('name', 255);
            $table->char('units', 20);
            $table->char('description', 255);
            $table->timestamps();
            $table->unique(['name', 'units']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solar_meters');
    }
};
