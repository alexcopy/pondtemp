<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFishFeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fish_feeds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('pond_id');
            $table->string('food_type');
            $table->double('weight');
            $table->string('description');
            $table->tinyInteger('is_disabled')->default(0);
            $table->integer('timestamp')->default(time());
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
        Schema::dropIfExists('fish_feeds');
    }
}
