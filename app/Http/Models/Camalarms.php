<?php

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;
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

            foreach ($json['value'] as $value) {
                if (isset($value['id'])) {
                    $msgid = $value['id'];
                    $value['msgid'] = $msgid;
                    unset($value['id']);
                }
                $stat['dev_id'] = $value['dev_id'];
                if (self::where('msgid', $value['msgid'])->where('dev_id', $value['dev_id'])->where('alarm_id', $value['alarm_id'])->count()) continue;
                Camalarms::create($value);
                $stat['count']++;
            }

        } catch (\Exception $exception) {

            (new Logger('Failed to add and parse JSON responce from P2P with resp:' . $response))->addCritical($exception->getMessage());
        }
        return $stat;
    }


}