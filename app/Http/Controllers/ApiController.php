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
        $weather = WeatherReading::orderBy('id', 'desc')->limit(300)->get();
        $tempReader = TempMeter::orderBy('id', 'desc')->limit(300)->get();
        return view('pages.index', compact(['weather', 'tempReader']));
    }

    public function tempdata(Request $request)
    {
        $insertWeatherEvery = 1200; //seconds
        $insertTemprEvery = 3600; //seconds

        TempMeter::writeToTextFile($request);
        $weather = WeatherReading::orderBy('id', 'desc')->limit(1)->get()->first();
        $tempReader = TempMeter::orderBy('id', 'desc')->limit(1)->get()->first();

        if ($weather && $weather->timestamp > $insertWeatherEvery) {
            WeatherReading::readDataAndWrite($request);
        }
        if ($tempReader && $tempReader->timestamp > $insertTemprEvery) {
            TempMeter::writeToDb((double)$request->get('ptemp', 0));
        }

    }
}
