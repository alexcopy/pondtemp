<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Gauges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gauges', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTimeTz('readingDate');
            $table->tinyInteger('pondLower')->default(null);
            $table->tinyInteger('pondUpper')->default(null);
            $table->tinyInteger('fl1')->default(null);
            $table->tinyInteger('fl2')->default(null);
            $table->tinyInteger('fl3')->default(null);
            $table->tinyInteger('strlow')->default(null);
            $table->integer('timestamp');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gauges');
    }
}
