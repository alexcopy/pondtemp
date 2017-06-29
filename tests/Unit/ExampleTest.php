<?php

use Illuminate\Support\Facades\Artisan;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class ExampleTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');
    }


    public function testExample()
    {

    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }
}
