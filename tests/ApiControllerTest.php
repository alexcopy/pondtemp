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
        $params = [
            'fltr1' => rand(1, 10),
            'fltr2' => rand(1, 10),
            'fltr3' => rand(1, 10),
            'pndlvl' => rand(1, 10),
            'ptemp' => 11.32,
            'shedhumid' => 39.00,
            'shedtemp' => 16.00,
            'streethumid' => rand(-50, 50),
            'strtemp' => rand(-50, 50),
            'pndlow' => rand(1, 10),
            'strlow' => rand(1, 10)
        ];

        $crawler = $this->get('/receiver?',$params);
        $gauges = Gauges::all()->first()->toArray();
        $weather = WeatherReading::all()->first()->toArray();
        $this->assertEquals(
            [
                'id' => 1,
                'readingDate' =>  $gauges['readingDate'],
                'pondLower' => $params['pndlow'],
                'pondUpper' => !$params['pndlvl'],
                'fl1' => $params['fltr1'],
                'fl2' => $params['fltr2'],
                'fl3' => $params['fltr3'],
                'strlow' => $params['strlow'],
                'timestamp' => 0

        ], $gauges);

        $this->assertEquals(
            [
                'id' => 1,
                'readingDate' =>  $weather['readingDate'],
                'pond' => $params['ptemp'],
                'shed' => $params['shedtemp'],
                'street' => $params['strtemp'],
                'shedhumid' => $params['shedhumid'],
                'streethumid' => $params['streethumid'],
                'room' => 0,
                'roomhumid' => 0,
                'timestamp' => time(),
                'location' => 0,
                'userId' => 10,
            ], $weather);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
