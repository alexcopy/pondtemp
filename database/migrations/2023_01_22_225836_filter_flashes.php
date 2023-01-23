<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('filter_flashes', function (Blueprint $table) {
            $table->increments('id');
            $table->float('max_current');
            $table->tinyInteger('meter_id');  //foreign key to meters table
            $table->float('duration')->default(0);
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
        Schema::dropIfExists('filter_flashes');
    }
};
