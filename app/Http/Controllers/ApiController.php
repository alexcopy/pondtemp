<?php

namespace App\Http\Controllers;

use App\Http\Models\Gauges;
use App\Http\Models\TempMeter;

use App\Http\Models\WeatherReading;
use Illuminate\Http\Request;

class ApiController extends Controller
{

    public function tempdata(Request $request)
    {
        $insertWeatherEvery = 1200; //seconds
        $insertInToPondDBEvery = 1200; //seconds
        TempMeter::writeToTextFile($request);


        if (!$this->checkDataValidity($request)) {
            return 'ERROR';
        }

        $weather = WeatherReading::orderBy('id', 'desc')->limit(1)->get()->first();
        $tempReader = TempMeter::orderBy('id', 'desc')->limit(1)->get()->first();
        Gauges::writeToDb($request);

        WeatherReading::parseAndWrite($request);
        if ($weather && ((time() - $insertWeatherEvery) > $weather->timestamp)) {
            //   WeatherReading::readDataAndWrite($request); //uncomment after all hardware tests
        }

        if ($tempReader && ((time() - $insertInToPondDBEvery) > $tempReader->timestamp)) {
            TempMeter::writeToDb((double)$request->get('ptemp', 0)); //umcoment after all hardware tests
        }

    }



    protected function checkDataValidity(Request $request)
    {
        $val = [];
        $val[] = (int)$request->get('ptemp', 0);
        $val[] = (int)$request->get('shedtemp', 0);
        $val[] = (int)$request->get('strtemp', 0);
        $val[] = (int)$request->get('shedhumid', 0);

        foreach ($val as $key => $value) {
            if ($value == 0) {
                unset($val[$key]);
            }
        }
        if (count($val) > 0) {
            return true;
        }
        return false;

    }

}
