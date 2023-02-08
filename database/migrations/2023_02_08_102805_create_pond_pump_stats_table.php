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
        Schema::create('pond_pump_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('Power'); //true or false if pump working
            $table->unsignedTinyInteger('device_id'); //Foreign key to DeviceTypes
            $table->unsignedTinyInteger('Fault'); //true or false if  Fault
            $table->unsignedTinyInteger('feeding'); //true or false if pump feeding
            $table->unsignedTinyInteger('flow_speed'); //The speed of pumping max 100 P
            $table->unsignedTinyInteger('mode'); // preset working mode
            $table->unsignedTinyInteger('from_main'); // energy comes from
            $table->unsignedInteger('power_show'); // preset working mode
            $table->unsignedInteger('voltage'); // preset working mode
            $table->unsignedInteger('rotating_speed'); // preset working mode
            $table->unsignedInteger('timer_power'); // preset working mode
            $table->unsignedInteger('timestamp'); // preset working mode
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
        Schema::dropIfExists('pond_pump_stats');
    }
};

//{'code': 'Power', 'value': True},
// {'code': 'Fault', 'value': 0},
// {'code': 'countdown_left', 'value': 600},
// {'code': 'feeding', 'value': False},
// {'code': 'P', 'value': 15},
// {'code': 'customize', 'value': ' '},
// {'code': 'day_and_night', 'value': ' '},
// {'code': 'spring_and_autumn', 'value': ' '},
// {'code': 'summer', 'value': ' '},
// {'code': 'winter', 'value': ' '},
// {'code': 'mode', 'value': '6'},
// {'code': 'power_show', 'value': '24'},
// {'code': 'voltage', 'value': '236'},
// {'code': 'rotating_speed', 'value': '1351'},
// {'code': 'timer_power', 'value': 0}]
