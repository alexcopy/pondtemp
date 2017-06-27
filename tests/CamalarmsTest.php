<?php


namespace App\Http\Models;


use Illuminate\Support\Facades\Artisan;

class CamalarmsTest extends \TestCase
{

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');
    }


    public function testChemicalAnalysis()
    {
        foreach (range(1, 10) as $item) {
            factory(Camalarms::class)->create();

        }
        $resutl = Camalarms::all()->toArray();
        $this->assertCount(10, $resutl);
    }


    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
