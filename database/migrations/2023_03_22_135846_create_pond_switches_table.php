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
        Schema::create('pond_switches', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('switch_id');
            $table->unsignedTinyInteger('status'); //on or off (1,0)
            $table->unsignedInteger('add_ele');
            $table->unsignedInteger('cur_power');
            $table->unsignedInteger('cur_current');
            $table->unsignedInteger('cur_voltage');
            $table->unsignedInteger('relay_status');
            $table->unsignedTinyInteger('from_main'); // energy comes from
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
        Schema::dropIfExists('pond_switches');
    }
};
