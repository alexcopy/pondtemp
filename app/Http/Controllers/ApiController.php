<?php

namespace App\Http\Controllers;

use App\Http\Models\Gauges;
use App\Http\Models\TempMeter;

use App\Http\Models\WeatherReading;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;

class ApiController extends Controller
{

    public function tempData(Request $request)
    {
        $insertWeatherEvery = 300; //seconds
        $insertInToPondDBEvery = 1200; //seconds
        try {
            TempMeter::writeToTextFile($request);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }

        if (!$this->checkDataValidity($request)) {
            return 'ERROR';
        }

        $weather = WeatherReading::orderBy('timestamp', 'desc')->limit(1)->get()->first();
        $tempReader = TempMeter::orderBy('timestamp', 'desc')->limit(1)->get()->first();
        Gauges::writeToDb($request);

        //  WeatherReading::parseAndWrite($request);// -- uncomment   during test lifecycle

        if ($weather && ((time() - $insertWeatherEvery) > $weather->timestamp)) {
            WeatherReading::parseAndWrite($request); //uncomment after all hardware tests
        }

        if ($tempReader && ((time() - $insertInToPondDBEvery) > $tempReader->timestamp)) {
            TempMeter::writeToDb((double)$request->get('ptemp', 0));
        }
        return time();
    }

    public function chemicalAnalyse(Request $request)
    {

    }


    protected function checkDataValidity(Request $request)
    {
        $val = [];
        $val[] = (int)$request->get('pressure', 0);
        $val[] = (int)$request->get('shedtemp', 0);
        $val[] = (int)$request->get('streettemp', 0);
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

    public function jsonGraph(Request $request)
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
        $current = WeatherReading::where('timestamp', ">", time() - 1200)->orderBy('timestamp', 'desc')->limit(10)->get();


        if (($startDate > $endDate) || (($endDate - $startDate) > 5076000)) {
            return response()->json([
                'data' => ['x' => [], 'StreetTemp' => [], 'PondTemp' => [], 'humid' => []],
            ], 400);
        }
        list($shedAver, $pondAver, $humShed, $humStr, $pressure, $shedTemp, $weather) = WeatherReading::getWetherAverege($startDate, $endDate);

        //Max, Aver, Min data above the charts
        $ids = [
            "maxstr" => round($weather->max('streettemp'), 1),
            "avgstr" => round($weather->avg('streettemp'), 1),
            "minstr" => round($weather->min('streettemp'), 1),
            "curstr" => round($current->avg('streettemp'), 1),
            "maxpnd" => round($weather->max('pond'), 1),
            "avgpnd" => round($weather->avg('pond'), 1),
            "minpnd" => round($weather->min('pond'), 1),
            "curpnd" => round($current->avg('pond'), 1)
        ];

        return response()->json([
            'data' => [
                'x' => array_keys($shedAver),
                'StreetTemp' => array_values($shedAver),
                'PondTemp' => array_values($pondAver),
                'ShedHum' => array_values($humShed),
                'StreetHum' => array_values($humStr),
                'Pressure' => array_values($pressure),
                'shedTemp' => array_values($shedTemp),
                'extr' => $ids
            ],
        ], 200);
    }

    public function filesStat(Request $request)
    {
        $dirList = explode(',', env('CAMS', ','));
        $ftpDir = storage_path('ftp');
        $data = [];
        $dateChunks = [];

        $datesInterval = range(Carbon::now()->startOfDay()->timestamp, time());
        $chunk = (int)count($datesInterval) / 24;
        $chunks = array_chunk($datesInterval, $chunk);

        foreach ($dirList as $dir) {
            $filesPath = $ftpDir . '/' . $dir . '/today';

            foreach (File::allFiles($filesPath) as $fileObj) {
                $lastModified = File::lastModified($fileObj->getPathName());
                $data[$dir][] = $lastModified;
            }
            if (empty($data[$dir])) {
                $data[$dir] = [];
            }
            asort($data[$dir]);
            array_walk($chunks, function (&$chunk) use ($data, $dir, &$dateChunks) {
                $end = Carbon::createFromTimestamp(end($chunk))->format('H:i');
                $dateChunks[$dir][$end] = count(array_intersect($data[$dir], $chunk));
                //$dateChunks[$dir][$end] = rand(1,30);
            });
        }
        $dirs = explode(',', env('CAMS', ','));
        $resp = ['data' => ['x' => array_keys($dateChunks['mamacam'])]];

        foreach ($dirs as $dir) {
            $resp['data'][$dir] = array_values($dateChunks[$dir]);
        }
        return response()->json($resp, 200);
    }

// TODO Move to separate service
    public static function getAlarmIds($camsObject)
    {
        $ifRegisterd = self::checkIsClientexists();
        if (!(int)$ifRegisterd->result) {
            (new Logger('client existence checks failed'));
            throw new \Exception("Client existence check is failed");
        }
        $cams = self::getUserDevicesParams();

        try {
            $alarmMsgs=self::getAlarmMessages($cams[0], 5,0);
        } catch (\Exception $exception) {


        }

    }


    protected static function getAlarmMessages($devObject, $max_count = 1, $last_fresh_time = 0)
    {
        $server = env('CAM_ALRM_SERV', '');
        $devObject->max_count = $max_count;
        $devObject->last_fresh_time = $last_fresh_time;

        $url = $server . '/GetAlarmMsg/AlarmSelectServletPicture?param='
            . \GuzzleHttp\json_encode($devObject);
        $params = [
            'defaults' => [
                'headers' => [
                    'User-Agent' => 'Dalvik/2.1.0 (Linux; U; Android 6.0.1; HTC Desire 530 Build/MMB29M)',
                    'Connection' => 'Keep-Alive',
                    'Accept-Encoding' => 'gzip'
                ]
            ]
        ];

        try {
            $page = (new Client($params))->request('GET', $url)->getBody()->getContents();
            return \GuzzleHttp\json_decode($page);

        } catch (\Exception $exception) {
            (new Logger('client existence check failed'))->addCritical($exception->getMessage());
            return (object)['result' => 0];
        }

    }

    /**
     * @return mixed|object
     */
    protected static function checkIsClientexists()
    {
        $server = env('CAM_ALRM_SERV', '');
        $url = $server . '/GetAlarmMsg/XGPhoneClientRegistered?param='
            . \GuzzleHttp\json_encode(self::getCheckParams());

        $params = [
            'defaults' => [
                'headers' => [
                    'User-Agent' => 'Dalvik/2.1.0 (Linux; U; Android 6.0.1; HTC Desire 530 Build/MMB29M)',
                    'Connection' => 'Keep-Alive',
                    'Accept-Encoding' => 'gzip'
                ]
            ]
        ];

        try {
            $page = (new Client($params))->request('GET', $url)->getBody()->getContents();
            return \GuzzleHttp\json_decode($page);
        } catch (\Exception $exception) {
            (new Logger('client existence check failed'))->addCritical($exception->getMessage());
            return (object)['result' => 0];
        }

    }

    /**
     * @return object
     */
    private static function getCheckParams()
    {
        return (object)[
            'client_id' => env('CAM_CLIENT_ID', ''),
            'phone_type' => env('CAM_PHONE_TYPE', ''),
            'apikey' => env('CAM_APIKEY', ''),
            'secretkey' => env('CAM_SECRETKEY', ''),
            'channel_id' => env('CAM_CHANNEL_ID', ''),
            'phone_num' => 'unknow',
            'user_id' => 'unknow',
            'vibrate' => 0,
            'sound' => 0,
            'sys_lan' => 'cn',
            'recv_msg_pri' => 1,
            'sound_file' => 'alarm.mp3',
        ];
    }

    private static function getUserDevicesParams()
    {
        $res = [];
        $cams = explode(',', env('CAM_IDS', []));
        foreach ($cams as $cam) {
            if (!$cam) continue;
            $res[] = (object)[
                'username' => env('CAM_LOGIN', 'admin'),
                'password' => env('CAM_PASS', 888888),
                'dev_id' => $cam,
            ];
        }
        return $res;
    }

}
