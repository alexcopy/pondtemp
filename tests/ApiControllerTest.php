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
    public function testGaugesData()
    {
        factory(Gauges::class)->create();
        factory(Gauges::class)->create();
        factory(Gauges::class)->create();
        factory(Gauges::class)->create();

        $gauges = Gauges::all();
        $this->assertCount(4, $gauges);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }

}
