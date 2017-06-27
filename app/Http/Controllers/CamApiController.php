<?php
/**
 * Created by PhpStorm.
 * User: alexredko
 * Date: 27/06/2017
 * Time: 23:44
 */

namespace App\Http\Controllers;


use App\Http\Models\Camalarms;
use App\Http\Services\CamService;
use Carbon\Carbon;
use Monolog\Logger;

class CamApiController
{
    public static function multiplePagedResult($numberOfHours = 24)
    {
        $stat = [];
        /** @var $chunk_mins interval in mins */
        $chunk_mins = 5;
        $devices = (new CamService())->getUserDevicesParams();
        $minsInOneDay = (60 * $numberOfHours) / $chunk_mins;

        foreach ($devices as $device) {
            $to_time = time();
            foreach (range(1, $minsInOneDay) as $chunkNumber) {
                $now = Carbon::now();
                $from_Time = $now->subMinutes($chunkNumber * $chunk_mins)->timestamp;
                $chk = [
                    'from_time' => $from_Time . rand(111, 999),
                    'to_time' => $to_time . rand(111, 999),
                ];
                $to_time = $from_Time;
                $page = (new CamService())->conditionalResult($chk['from_time'], $chk['to_time'], $device);
                $stat[] = Camalarms::writeJsonToDb($page);
                sleep(rand(5, 20));
            }
        }
        return $stat;
    }


    public static function processAlarmMessages()
    {
        $stat = [];
        $devices = (new CamService())->getUserDevicesParams();
        foreach ($devices as $device) {
            $proc = Camalarms::where('dev_id', $device->dev_id)->where('processed', 0)->where('process_fail', '<', 10);
            $stat[$device->dev_id]['dev_id'] = $device->dev_id;
            $stat[$device->dev_id]['count'] = 0;
            $stat[$device->dev_id]['fails'] = 0;
            if ($proc->count() == 0) continue;
            foreach ($proc->get() as $img) {
                $jsonImg = (new CamService())->processImage($img, $device);
                if (isset($jsonImg['result']) && ($jsonImg['result'] == 0)) {
                    Camalarms::where('id', $img->id)->increment('process_fail');
                    $stat[$device->dev_id]['fails']++;
                    sleep(rand(5, 20));
                    continue;
                }
                $result = (new CamService())->convertImageAndSave($jsonImg, $device);
                if ($result) {
                    Camalarms::where('id', $img->id)->update(['processed' => 1, 'processed_at' => time()]);
                    $stat[$device->dev_id]['count']++;
                }
                sleep(rand(1, 5));
            }
        }
        return $stat;
    }


    public static function getAlarmMessagesAndWriteInDb()
    {
        $stat = [];
        $ifRegisterd = (new CamService())->checkIsClientexists();
        if (!(int)$ifRegisterd->result) {
            (new Logger('client existence checks failed'));
            throw new \Exception("Client existence checks  failed");
        }
        $cams = (new CamService())->getUserDevicesParams();
        foreach ($cams as $cam) {
            $alarmMsg = (new CamService())->getAlarmMessages($cam, 5, 0);
            $stat[] = Camalarms::writeJsonToDb($alarmMsg);
        }
        return $stat;
    }


}