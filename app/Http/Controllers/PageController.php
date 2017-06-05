<?php


namespace App\Http\Controllers;


use App\Http\Models\Gauges;
use App\Http\Models\TempMeter;
use App\Http\Models\WeatherReading;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{

    public function index()
    {
        $weather = WeatherReading::orderBy('timestamp', 'desc')->limit(100)->get();
        $tempReader = TempMeter::orderBy('timestamp', 'desc')->limit(100)->get();
        $guages = Gauges::orderBy('timestamp', 'desc')->limit(100)->get();
        return view('pages.index', compact(['weather', 'tempReader', 'guages']));
    }

    public function ping()
    {
        $weather = WeatherReading::orderBy('timestamp', 'desc')->limit(10)->get();
        $tempReader = TempMeter::orderBy('timestamp', 'desc')->limit(10)->get();
        return view('pages.index', compact(['weather', 'tempReader']));
    }

    public function graph(Request $request)
    {
        $weather = WeatherReading::orderBy('timestamp', 'desc')->limit(10)->get();
        return view('pages.graph', compact(['weather']));
    }

    public function allCamFiles(Request $request)
    {
        $dirList = ['mamacam', 'pond', 'koridor'];
        $ftpDir = storage_path() . '/ftp';
        $dirFiles = [];


        foreach ($dirList as $dir) {
            $filesPath = $ftpDir . '/' . $dir . '/today';

            $dirFiles['files'][$dir] = File::allFiles($filesPath);
            $dirFiles['dirs'][$dir] = File::directories($ftpDir . '/' . $dir);
            $dirFiles['changed'][$dir] = Carbon::createFromTimestamp(File::lastModified($filesPath))->format('d/m H:i');
        }

        return view('pages.camfiles', compact(['dirFiles']));
    }

}