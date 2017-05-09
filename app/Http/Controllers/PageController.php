<?php


namespace App\Http\Controllers;


use App\Http\Models\Gauges;
use App\Http\Models\TempMeter;
use App\Http\Models\WeatherReading;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class PageController extends Controller
{

    public function index()
    {
        $weather = WeatherReading::orderBy('id', 'desc')->limit(50)->get();
        $tempReader = TempMeter::orderBy('id', 'desc')->limit(50)->get();
        $guages = Gauges::orderBy('id', 'desc')->limit(50)->get();

//        Input::get('email')
        return view('pages.index', compact(['weather', 'tempReader', 'guages']));
    }

    public function ping()
    {
        $weather = WeatherReading::orderBy('id', 'desc')->limit(2)->get();
        $tempReader = TempMeter::orderBy('id', 'desc')->limit(2)->get();
        return view('pages.index', compact(['weather', 'tempReader']));
    }

    public function graph(Request $request)
    {
        list($shedAver, $pondAver, $humAver) = WeatherReading::getWetherAverege(Carbon::yesterday()->timestamp);
        return view('pages.graph', compact(['humAver', 'shedAver', 'pondAver']));
    }


}