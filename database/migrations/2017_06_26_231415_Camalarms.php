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
            $table->string('alarm_msg')->default('nan');
            $table->dateTimeTz('alarm_time')->default(\Carbon\Carbon::now()->format('Y-m-d H:i:s'));
            $table->string('has_position')->default('0');
            $table->integer('version_num')->default(0);
            $table->string('alarm_image')->default(' ');;
            $table->integer('alarm_type')->default(0);
            $table->integer('dev_id');
            $table->integer('alarm_id');
            $table->integer('alarm_level')->default(0);
            $table->integer('last_fresh_time');
            $table->integer('image_id');
            $table->string('ip');
            $table->boolean('processed')->default(false);
            $table->integer('timestamp')->default(time());
            $table->integer('processed_at')->default(0);
            $table->smallInteger('process_fail')->default(0);
            $table->smallInteger('in_process')->default(0);
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
