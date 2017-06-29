<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Models\WeatherReading;
use Carbon\Carbon;
use SplFileObject;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function restoreData()
    {
        $local_file = storage_path() . "/temp.txt";
        $tint = 300;
        $pattern = '~(?P<date>\d{1,}-\d{1,}-\d{1,}\s+\d{1,2}\:\d{1,2}\:\d{1,2})\s+pond temp:(?P<pt>[^\:]+)shedTemp:(?P<sht>[^\:]+)streetTemp:(?P<strT>[^\:]+)shedhumid:(?P<humid>[^\:]+)streethumid:(?P<strH>[^\:]+)roomtemp.+pressure(\s)?:(?P<press>[^\:]+)location~iU';
        $file = new SplFileObject ($local_file, "r");

        while (!$file->eof()) {
            $line = $file->fgets();
            if (!preg_match($pattern, $line, $mtchs)) continue;
            $tmsp = Carbon::parse($mtchs['date'])->timestamp;

            $res = WeatherReading::whereBetween('timestamp', [$tmsp - $tint, $tmsp + $tint])->count();
            if ($res == 0) {
                $mtchs['timestamp'] = $tmsp;
                WeatherReading::restoreFromTextFile($mtchs);
                echo "Done for: " . $mtchs['date'] . "<br >";
            }
        }

    }
}
