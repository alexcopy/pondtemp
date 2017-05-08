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
        $shedAver = [];
        $pondAver = [];
        $humAver=[];


        $weather = WeatherReading::where('timestamp', '>',  Carbon::yesterday()->timestamp)->orderBy('id', 'desc')->get();

        $chunkSize = round($weather->count()/24, 0);
        foreach ($weather->chunk($chunkSize) as $item) {
            $date = $item->slice( $chunkSize/2, 1)->last()->readingDate;
            $readingDate =Carbon::createFromFormat('Y-m-d H:m:s', $date)->format('H:m');
            $shedAver[$readingDate] = round($item->avg('shed'), 1);
            $pondAver[$readingDate] = round($item->avg('pond'),1);
            $humAver[$readingDate] = round($item->avg('shedhumid'),1);
        }
        $shedAver=array_reverse( $shedAver);
        $pondAver=array_reverse( $pondAver);
        $humAver=array_reverse( $humAver);


        return view('pages.graph', compact(['humAver', 'shedAver', 'pondAver']));
    }
}