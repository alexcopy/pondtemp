<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WaterChanges extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('water_changes', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTimeTz('changeDate');
            $table->string('description');
            $table->double('readingBefore');
            $table->double('readingAfter');
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
        Schema::dropIfExists('water_changes');
    }
}
