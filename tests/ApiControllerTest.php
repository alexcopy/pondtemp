<?php
/**
 * Created by PhpStorm.
 * User: alexredko
 * Date: 28/04/2017
 * Time: 13:05
 */

namespace App\Http\Controllers;


use App\Http\Models\Gauges;
use Illuminate\Support\Facades\Artisan;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ApiControllerTest extends \PHPUnit_Framework_TestCase
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

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }

}
