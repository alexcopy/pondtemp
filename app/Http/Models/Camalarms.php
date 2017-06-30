<?php

namespace App\Http\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;

class Camalarms extends Model
{

    public $timestamps = false;
    protected $fillable = [
        "msgid",
        "alarm_msg",
        "alarm_time",
        "has_position",
        "version_num",
        "alarm_image",
        "alarm_type",
        "dev_id",
        "alarm_id",
        "alarm_level",
        "last_fresh_time",
        "image_id",
        "ip",
        "processed_at",
    ];


    public static function writeJsonToDb($response)
    {
        $stat = ['count' => 0, 'dev_id' => 0];
        try {
            $json = \GuzzleHttp\json_decode($response, true);
            if (!isset($json['value'])) throw new \Exception('Empty Value has Passed');
            if (isset($json['result']) && $json['result'] == 0) throw new \Exception('Empty Result has Passed');

            foreach ($json['value'] as $value) {
                if (isset($value['id'])) {
                    $msgid = $value['id'];
                    $value['msgid'] = $msgid;
                    unset($value['id']);
                }
                $stat['dev_id'] = $value['dev_id'];
                if (self::where('msgid', $value['msgid'])->where('dev_id', $value['dev_id'])->where('alarm_id', $value['alarm_id'])->count()) continue;
                $value['timestamp'] = time();
                $value['alarm_stamp'] = self::getTimeStamp($value['alarm_time'], $value['last_fresh_time']);
                Camalarms::create($value);
                $stat['count']++;
            }

        } catch (\Exception $exception) {

            Log::critical('Failed to add and parse JSON responce from P2P with resp:' .  $response  .'  Err Messsage is: ' . $exception->getMessage());
        }
        return $stat;
    }


    private static function getTimeStamp($alarm_time, $last_fresh_time)
    {

        $timestampChina = Carbon::parse($alarm_time, 'Asia/Shanghai')->timestamp;
        $timestampLondon = Carbon::parse($alarm_time, 'Europe/London')->timestamp;
        $fresh = (string)$last_fresh_time;
        $freshTime = substr($fresh, 0, 10);
        $diff = abs((int)$timestampLondon - (int)$freshTime);
        if ($diff > 4600) {
            $alarm_stamp = $timestampChina;
        } else {
            $alarm_stamp = $timestampLondon;
        }
        return $alarm_stamp;
    }
}