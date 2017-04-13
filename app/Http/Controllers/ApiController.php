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

    public function tempdata(Request $request)
    {
        $insertEvery=1200; //seconds
        TempMeter::writeToDb((double)$request->get('ptemp', 0));
        TempMeter::writeToTextFile($request);
        WeatherReading::readDataAndWrite($request);
    }
}
