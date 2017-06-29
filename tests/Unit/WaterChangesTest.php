<?php
/**
 * Created by PhpStorm.
 * User: alexredko
 * Date: 23/05/2017
 * Time: 13:20
 */

namespace App\Http\Models;


use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class WaterChangesTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');
    }


    public function testWaterChanges()
    {
        foreach (range(1, 10) as $item) {
            factory(WaterChanges::class)->create();

        }
        $resutl = WaterChanges::all()->toArray();
        $this->assertCount(10, $resutl);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
