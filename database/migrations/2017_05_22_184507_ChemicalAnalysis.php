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
            $table->double('nO2');
            $table->double('nO3');
            $table->double('nH4');
            $table->double('ph');
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
