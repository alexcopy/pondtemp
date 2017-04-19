<?php

namespace App\Http\Controllers;

use App\Http\Models\TempMeter;

use App\Http\Models\WeatherReading;
use Illuminate\Http\Request;

class ApiController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        $weather = WeatherReading::orderBy('id', 'desc')->limit(500)->get();
        $tempReader = TempMeter::orderBy('id', 'desc')->limit(500)->get();
        return view('pages.index', compact(['weather', 'tempReader']));
    }

    public function tempdata(Request $request)
    {
        $insertWeatherEvery = 1200; //seconds
        $insertInToPondDBEvery = 3600; //seconds
        TempMeter::writeToTextFile($request);


        if (!$this->checkDataValidity($request)) {
            return 'ERROR';
        }

        $weather = WeatherReading::orderBy('id', 'desc')->limit(1)->get()->first();
        $tempReader = TempMeter::orderBy('id', 'desc')->limit(1)->get()->first();


        WeatherReading::readDataAndWrite($request);
        if ($weather && ((time() - $insertWeatherEvery) > $weather->timestamp)) {
         //   WeatherReading::readDataAndWrite($request); //uncomment after all hardware tests
        }
            TempMeter::writeToDb((double)$request->get('ptemp', 0));
        if ($tempReader && ((time() - $insertInToPondDBEvery) > $tempReader->timestamp)) {
           // TempMeter::writeToDb((double)$request->get('ptemp', 0)); //umcoment after all hardware tests
        }

    }

    public function ping()
    {
        $weather = WeatherReading::orderBy('id', 'desc')->limit(2)->get();
        $tempReader = TempMeter::orderBy('id', 'desc')->limit(2)->get();
        return view('pages.index', compact(['weather', 'tempReader']));
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
