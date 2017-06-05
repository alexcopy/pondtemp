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
            $dirFiles['changed'][$dir] = File::lastModified($filesPath);
            $dirFiles['size'][$dir] =  self::human_filesize($filesPath);
        }
        return view('pages.camfiles', compact(['dirFiles']));
    }

    public static function human_filesize($path, $decimals = 2)
    {
        $io = popen ( '/usr/bin/du -sk ' . $path, 'r' );
        $size = fgets ( $io, 4096);
        $size = substr ( $size, 0, strpos ( $size, "\t" ) );
        pclose ( $io );

        return $size;
//
  }
}
//        $size = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
//        $factor = floor((strlen($bytes) - 1) / 3);
//        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . $size[$factor];

//public static function human_filesize($path, $decimals = 2)
//{
//    $io = popen ( '/usr/bin/du -sk ' . $path, 'r' );
//    $size = fgets ( $io, 4096);
//    $sizeB = substr ( $size, 0, strpos ( $size, "\t" ) )*1024;
//    pclose ( $io );
//    $sizeH = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
//    $factor = floor((strlen($sizeB) - 1) / 3);
//    return sprintf("%.{$decimals}f", $sizeB / pow(1024, $factor)) . $sizeH[$factor];
//
//}