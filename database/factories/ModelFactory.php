<?php



$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});


$factory->define(App\Http\Models\WaterChanges::class, function (Faker\Generator $faker) {
    return [
        'changeDate' => $faker->date('Y-m-d H:i:s'),
        'description' => $faker->text(50),
        'readingBefore' => $faker->randomFloat(),
        'readingAfter' => $faker->randomFloat(),
    ];
});

$factory->define(App\Http\Models\Gauges::class, function (Faker\Generator $faker) {
    $date = $faker->date('Y-m-d H:i:s');
    return [
        'readingDate' => $date,
        'pondLower' => $faker->randomDigit,
        'pondUpper' => $faker->randomDigit,
        'fl1' => $faker->randomDigit,
        'fl2' => $faker->randomDigit,
        'fl3' => $faker->randomDigit,
        'strlow' =>$faker->randomDigit,
        'timestamp' => \Carbon\Carbon::parse($date)->timestamp,
    ];
});
$factory->define(App\Http\Models\WeatherReading::class, function (Faker\Generator $faker) {
    $date = $faker->date('Y-m-d H:i:s');
    return [
        'readingDate' => $date,
        'pond' => $faker->randomFloat(),
        'streettemp' => $faker->randomFloat(),
        'shedtemp' => $faker->randomFloat(),
        'shedhumid' => $faker->randomFloat(),
        'streethumid' => $faker->randomFloat(),
        'room' => $faker->randomFloat(),
        'roomhumid' => $faker->randomFloat(),
        'location' => $faker->randomFloat(),
        'pressure' => $faker->randomFloat(),
        'timestamp' => \Carbon\Carbon::parse($date)->timestamp,
        'userId' => $faker->randomDigit
    ];
});


$factory->define(App\Http\Models\ChemicalAnalyses::class, function (Faker\Generator $faker) {
    return [
        'date' => $faker->date('Y-m-d H:i:s'),
        'nO2' => $faker->randomFloat(),
        'nO3' => $faker->randomFloat(),
        'nH4' => $faker->randomFloat(),
        'ph' => $faker->randomFloat(),
    ];
});
$factory->define(App\Http\Models\Chemicals::class, function (Faker\Generator $faker) {
    return [
        'date' => $faker->date('Y-m-d H:i:s'),
        'qty' => $faker->randomDigit,
        'reason' => $faker->text(100),
        'type' => $faker->text(100),
    ];
});
$factory->define(App\Http\Models\Devices::class, function (Faker\Generator $faker) {
    return [
        'deviceName' => $faker->text(100),
        'deviceType' => $faker->text(100),
        'description' => $faker->text(100),
    ];
});
$factory->define(App\Http\Models\FilterPumpCleanings::class, function (Faker\Generator $faker) {
    return [
        'cleaningDate' => $faker->date('Y-m-d H:i:s'),
        'description' => $faker->text(100),
        'pumpid' => $faker->randomDigit,
        'readings' => $faker->randomFloat(),
    ];
});

$factory->define(App\Http\Models\Locations::class, function (Faker\Generator $faker) {
    return [
        'streetAddress' => $faker->streetAddress,
        'postalCode' => $faker->postcode,
        'city' => $faker->city,
        'county' => $faker->country,
    ];
});

$factory->define(App\Http\Models\Tanks::class, function (Faker\Generator $faker) {
    return [
        'tankName' => $faker->name,
        'tankType' => $faker->text(100),
        'description' => $faker->text(100),
    ];
});

$factory->define(App\Http\Models\MeterReadings::class, function (Faker\Generator $faker) {
    return [
        'readingDate' => $faker->date('Y-m-d H:i:s'),
        'readings' => $faker->randomFloat()
    ];
});


$factory->define(App\Http\Models\LiveStocks::class, function (Faker\Generator $faker) {
    return [
        'date' => $faker->date('Y-m-d H:i:s'),
        'reason' => $faker->text(100),
        'description' => $faker->text(100),
        'qty' => $faker->randomDigit,
        'readings' => $faker->randomFloat(),
    ];
});

$factory->define(App\Http\Models\Camalarms::class, function (Faker\Generator $faker) {
    return [
        'msgid' => $faker->unixTime,
        'alarm_time' => $faker->date('Y-m-d H:i:s'),
        'alarm_msg' => $faker->text(100),
        'has_position' => $faker->text(100),
        'version_num' => $faker->randomDigit,
        'alarm_image' => $faker->text(100),
        'alarm_type' => $faker->randomDigit,
        'alarm_stamp' =>  $faker->unixTime,
        'dev_id' => $faker->unixTime,
        'alarm_id' => $faker->unixTime,
        'alarm_level' => $faker->randomDigit,
        'last_fresh_time' => microtime(),
        'image_id' => $faker->unixTime,
        'ip' => $faker->ipv4,
    ];
});

