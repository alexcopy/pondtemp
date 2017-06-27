<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Camalarms extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('camalarms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('msgid');
            $table->string('alarm_msg');
            $table->dateTimeTz('alarm_time');
            $table->string('has_position');
            $table->integer('version_num');
            $table->string('alarm_image');
            $table->integer('alarm_type');
            $table->integer('dev_id');
            $table->integer('alarm_id');
            $table->integer('alarm_level');
            $table->integer('last_fresh_time');
            $table->integer('image_id');
            $table->string('ip');
            $table->boolean('processed')->default(false);
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
        Schema::dropIfExists('camalarms');
    }
}
