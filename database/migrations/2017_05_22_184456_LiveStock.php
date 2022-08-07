<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LiveStock extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_stocks', function (Blueprint $table) {

            $table->increments('id');
            $table->dateTimeTz('date');
            $table->string('reason');
            $table->string('description');
            $table->integer('qty');
            $table->double('readings');
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
        Schema::dropIfExists('live_stocks');
    }
}
