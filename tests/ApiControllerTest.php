<?php
/**
 * Created by PhpStorm.
 * User: alexredko
 * Date: 28/04/2017
 * Time: 13:05
 */

namespace App\Http\Controllers;


use App\Http\Models\Gauges;
use App\Http\Models\WeatherReading;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiControllerTest extends \TestCase
{


    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');
    }

    public function testGaugesWriteData()
    {
        factory(Gauges::class)->create();
        factory(Gauges::class)->create();


        $gauges = Gauges::all()->toArray();
        $this->assertCount(2, $gauges);
        $this->assertEquals([
            [
                'id' => 1,
                'readingDate' => $gauges[0]['readingDate'],
                'pondLower' => $gauges[0]['pondLower'],
                'pondUpper' => $gauges[0]['pondUpper'],
                'fl1' => $gauges[0]['fl1'],
                'fl2' => $gauges[0]['fl2'],
                'fl3' => $gauges[0]['fl3'],
                'strlow' => $gauges[0]['strlow'],
                'timestamp' => $gauges[0]['timestamp']
            ],
            [
                'id' => 2,
                'readingDate' => $gauges[1]['readingDate'],
                'pondLower' => $gauges[1]['pondLower'],
                'pondUpper' => $gauges[1]['pondUpper'],
                'fl1' => $gauges[1]['fl1'],
                'fl2' => $gauges[1]['fl2'],
                'fl3' => $gauges[1]['fl3'],
                'strlow' => $gauges[1]['strlow'],
                'timestamp' => $gauges[1]['timestamp']
            ]
        ], $gauges);

    }


    public function testGetParams()
    {
        foreach (range(1, 10) as $item) {
            factory(WeatherReading::class)->create();

        }
        $pars = [
            'readingDate' => (new DateTime())->format('Y-m-d H:i:s'),
            'pond' => 10,
            'streettemp' => 10,
            'shedtemp' => 10,
            'shedhumid' => 10,
            'streethumid' => 10,
            'room' => 10,
            'roomhumid' => 10,
            'location' => 10,
            'pressure' => 10,
            'timestamp' => 10,
            'userId' => 10
        ];

        WeatherReading::parseAndWrite(new Request($pars));
        $weather = WeatherReading::all()->toArray();
        $this->assertCount(11, $weather);
        $params = [
            'readingDate' => (new DateTime())->format('Y-m-d H:i:s'),
            'pond' => 11.32,
            'streettemp' => 39.00,
            'shedtemp' => 16.00,
            'pressure' => 16.00,
            'shedhumid' => rand(-50, 50),
            'streethumid' => rand(-50, 50),
            'room' => rand(-50, 50),
            'roomhumid' => rand(-50, 50),
            'pndlow' => rand(1, 10),
            'strlow' => rand(1, 10),
            'location' => rand(1, 10),
            'userId' => rand(1, 10),
            'timestamp' => time(),

        ];
        WeatherReading::parseAndWrite(new Request($params));
        $weather = WeatherReading::all()->toArray();
        $this->assertCount(12, $weather);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
