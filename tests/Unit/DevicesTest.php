<?php
/**
 * Created by PhpStorm.
 * User: alexredko
 * Date: 23/05/2017
 * Time: 13:18
 */

namespace App\Http\Models;


use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;


class DevicesTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');
    }


    public function testDevices()
    {
        foreach (range(1, 10) as $item) {
            factory(Devices::class)->create();

        }
        $resutl = Devices::all()->toArray();
        $this->assertCount(10, $resutl);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
