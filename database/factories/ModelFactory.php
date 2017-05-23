<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

$factory->define(App\Http\Models\Gauges::class, function (Faker\Generator $faker) {
    return [
        'readingDate' => $faker->date('Y-m-d H:i:s'),
        'pondLower' => $faker->randomDigit,
        'pondUpper' => $faker->randomDigit,
        'fl1' => $faker->randomDigit,
        'fl2' => $faker->randomDigit,
        'fl3' => $faker->randomDigit,
        'strlow' => $faker->randomDigit,
        'timestamp' =>time()
    ];
});
$factory->define(App\Http\Models\WeatherReading::class, function (Faker\Generator $faker) {
    return [
        'readingDate'=>$faker->date('Y-m-d H:i:s'),
        'pond'=> $faker->randomDigit,
        'streettemp'=> $faker->randomDigit,
        'shedtemp'=> $faker->randomDigit,
        'shedhumid'=> $faker->randomDigit,
        'streethumid'=> $faker->randomDigit,
        'room'=> $faker->randomDigit,
        'roomhumid'=> $faker->randomDigit,
        'location'=> $faker->randomDigit,
        'pressure'=> $faker->randomDigit,
        'timestamp' => $faker->randomDigit,
        'userId'=> $faker->randomDigit
    ];
});