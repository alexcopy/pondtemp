<?php

namespace App\Http\Models;


use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ChemicalsTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');
    }


    public function testChemicals()
    {

        foreach (range(1, 10) as $item) {
            factory(Chemicals::class)->create();

        }
        $resutl = Chemicals::all()->toArray();
        $this->assertCount(10, $resutl);
    }


    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
