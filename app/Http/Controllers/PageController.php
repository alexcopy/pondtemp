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
        $weather = WeatherReading::orderBy('id', 'desc')->limit(500)->get();
        $tempReader = TempMeter::orderBy('id', 'desc')->limit(500)->get();
        $guages = Gauges::orderBy('id', 'desc')->limit(500)->get();

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
        $shedAver = [];
        $pondAver = [];
        $date = (new DateTime())->format('Y-m-d');
        $weather = WeatherReading::where('readingDate', 'LIKE', $date . '%')->get();

        foreach ($weather->chunk(12) as $item) {
            $shedAver[$item->last()->readingDate] = round($item->avg('shed'), 1);
            $pondAver[$item->last()->readingDate] = round($item->avg('pond'),1);
        }

        return view('pages.graph', compact(['weather', 'pondTemp', 'shedAver', 'pondAver']));
    }
}