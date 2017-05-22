<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChemicalAnalysis extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('chemical_analysis', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTimeTz('date');
            $table->string('nO2');
            $table->string('nO3');
            $table->string('nH4');
            $table->string('ph');
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
        Schema::dropIfExists('chemical_analysis');
    }
}
