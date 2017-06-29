<?php
/**
 * Created by PhpStorm.
 * User: alexredko
 * Date: 23/05/2017
 * Time: 13:19
 */

namespace App\Http\Models;


use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class TanksTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');
    }


    public function testTanks()
    {
        foreach (range(1, 10) as $item) {
            factory(Tanks::class)->create();

        }
        $resutl = Tanks::all()->toArray();
        $this->assertCount(10, $resutl);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
