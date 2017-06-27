<?php


namespace App\Http\Models;


use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ChemicalAnalysesTest extends \TestCase
{

    public function setUp()
    {
        parent::setUp();
        Artisan::call('migrate');
    }


    public function testChemicalAnalysis()
    {
        foreach (range(1, 10) as $item) {
            factory(ChemicalAnalyses::class)->create();

        }
        $resutl = ChemicalAnalyses::all()->toArray();
        $this->assertCount(10, $resutl);
    }

    public function testParseAndWrite()
    {
        $params = [
            'date' => (new DateTime())->format('Y-m-d H:i:s'),
            'nO2' => 1, 234,
            'nO3' => 1, 45,
            'nH4' => 3, 45,
            'ph' => 6, 78
        ];

        ChemicalAnalyses::parseAndWrite(new Request($params));
        $resutl = ChemicalAnalyses::all()->toArray();
        $this->assertCount(1, $resutl);
    }

    public function tearDown()
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }

}
