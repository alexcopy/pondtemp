<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCamerasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cameras', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cam_id')->default(0);
            $table->string('name');
            $table->string('realpath')->default('');
            $table->string('login');
            $table->string('password');
            $table->string('alarmServerUrl');
            $table->integer('port');
            $table->integer('channel')->default(0);
            $table->string('clientExistsUrl')->default('');
            $table->tinyInteger('is_cloudBased')->default(0);
            $table->tinyInteger('is_enabled')->default(1);
            $table->text('description');
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
        Schema::dropIfExists('cameras');
    }
}
