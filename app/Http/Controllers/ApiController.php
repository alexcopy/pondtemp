<?php

namespace App\Http\Controllers;

use App\Http\Models\Gauges;
use App\Http\Models\TempMeter;

use App\Http\Models\WeatherReading;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiController extends Controller
{

    public function tempData(Request $request)
    {
        $insertWeatherEvery = 300; //seconds
        $insertInToPondDBEvery = 1200; //seconds
        TempMeter::writeToTextFile($request);


        if (!$this->checkDataValidity($request)) {
            return 'ERROR';
        }

        $weather = WeatherReading::orderBy('id', 'desc')->limit(1)->get()->first();
        $tempReader = TempMeter::orderBy('id', 'desc')->limit(1)->get()->first();
        Gauges::writeToDb($request);

        //  WeatherReading::parseAndWrite($request);// -- uncomment   during test lifecycle

        if ($weather && ((time() - $insertWeatherEvery) > $weather->timestamp)) {
            WeatherReading::parseAndWrite($request); //uncomment after all hardware tests
        }

        if ($tempReader && ((time() - $insertInToPondDBEvery) > $tempReader->timestamp)) {
            TempMeter::writeToDb((double)$request->get('ptemp', 0)); //umcoment after all hardware tests
        }
        return time();
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

    public function dateInterval(Request $request)
    {
        if (!$request->ajax()) {
            return "Not Allowed";
        }
        $startDate = $request->get('startDate', Carbon::yesterday()->toDateTimeString());
        $endDate = $request->get('endDate', Carbon::now()->toDateTimeString());

        if ($startDate == '' || $endDate == '') {
            $startDate = Carbon::yesterday()->toDateTimeString();
            $endDate = Carbon::now()->toDateTimeString();
        }

        $startDate = Carbon::parse($startDate)->timestamp;
        $endDate = Carbon::parse($endDate)->timestamp;
        //TODO change later (it's not proper date, get current date)
        $current = WeatherReading::orderBy('id', 'desc')->limit(10)->get();


        if (($startDate > $endDate) || (($endDate - $startDate) > 5076000)) {
            return response()->json([
                'data' => ['x' => [], 'StreetTemp' => [], 'PondTemp' => [], 'humid' => []],
            ], 400);
        }
        list($shedAver, $pondAver, $humAver, $weather) = WeatherReading::getWetherAverege($startDate, $endDate);
        $ids = [
            "curstr"=>round($current->avg('shed'), 1),
            "curpnd"=>round($current->avg('pond'), 1),

            "maxstr"=>round($weather->max('shed'), 1),
            "minstr"=>round($weather->min('shed'), 1),
            "maxpnd"=>round($weather->max('pond'), 1),
            "minpnd"=>round($weather->min('pond'), 1),
            "avgcurstr"=>round($weather->avg('shed'), 1),
            "avgcurpnd"=>round($weather->avg('pond'), 1)
        ];

        return response()->json([
            'data' => [
                'x' => array_keys($shedAver),
                'StreetTemp' => array_values($shedAver),
                'PondTemp' => array_values($pondAver),
                'humid' => array_values($humAver),
                'extr'=>$ids
            ],
        ], 200);
    }

}
