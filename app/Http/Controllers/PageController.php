<?php


namespace App\Http\Controllers;


use App\Http\Models\Gauges;
use App\Http\Models\TempMeter;
use App\Http\Models\WeatherReading;
use Illuminate\Http\Request;

class PageController extends Controller
{

    public function index()
    {
        $weather = WeatherReading::orderBy('id', 'desc')->limit(500)->get();
        $tempReader = TempMeter::orderBy('id', 'desc')->limit(500)->get();
        $guages = Gauges::orderBy('id', 'desc')->limit(500)->get();


        return view('pages.index', compact(['weather', 'tempReader', 'guages']));
    }

    public function ping()
    {
        $weather = WeatherReading::orderBy('id', 'desc')->limit(2)->get();
        $tempReader = TempMeter::orderBy('id', 'desc')->limit(2)->get();
        return view('pages.index', compact(['weather', 'tempReader']));
    }

    public function graph()
    {
        $weather = WeatherReading::orderBy('id', 'desc')->limit(100)->get(['pond','shed', 'readingDate']);
        return view('pages.graph', compact(['weather']));
    }
}