<?php
/**
 * Created by PhpStorm.
 * User: alexredko
 * Date: 23/05/2017
 * Time: 13:19
 */

namespace App\Http\Models;


use Illuminate\Support\Facades\Artisan;

class LiveStocksTest extends \TestCase
{

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');
    }


    public function testLiveStocks()
    {
        foreach (range(1, 10) as $item) {
            factory(LiveStocks::class)->create();

        }
        $resutl = LiveStocks::all()->toArray();
        $this->assertCount(10, $resutl);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
