<?php

namespace App\Http\Controllers;


use App\Http\Models\Camalarms;
use App\Http\Models\Cameras;
use App\Http\Services\CamService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
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
        $i = 0;
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
                $i++;
                $stat[$i] = Camalarms::writeJsonToDb($page);
                $info = $i . ": Got results for dev id: " . $device->dev_id . " with message:  " . self::getNumbeResponceInfo($page) . ' q-ty wrote to DB: '.  $stat[$i]['count'].'  and time interval is: ' . Carbon::createFromTimestamp($to_time)->format('d/m H:i') . "\n";
                echo $info;
                Log::info($info);
            }
        }
        return $stat;
    }

    protected static function getNumbeResponceInfo($page)
    {
        try {
            $json = json_decode($page, true);
            if (isset($json['result']) && $json['result'] == 0){
                return 'zero results benn recieved';
            }
            if (isset($json['result'])){
                return "Have received: ".$json['result']. "  results";
            }

        } catch (\Exception $exception) {

            return 'got Error while trying to parce json string';

        }
    }

    public static function processAlarmMessages()
    {
        $stat = [];
        $i=0;
        $devices = (new CamService())->getUserDevicesParams();
        foreach ($devices as $device) {
            $proc = Camalarms::where('dev_id', $device->dev_id)
                ->where('processed', 0)
                ->where('process_fail', '<', 10)
                ->where('in_process', 0)->limit(20);

            $stat[$device->dev_id]['dev_id'] = $device->dev_id;
            $stat[$device->dev_id]['count'] = 0;
            $stat[$device->dev_id]['fails'] = 0;
            if ($proc->count() == 0) continue;
            foreach ($proc->get() as $img) {
                $jsonImg = (new CamService())->processImage($img, $device);
                if (isset($jsonImg['result']) && ($jsonImg['result'] == 0)) {
                    Camalarms::where('id', $img->id)->increment('process_fail');
                    Camalarms::where('id', $img->id)->update(['in_process' => 0]);
                    $stat[$device->dev_id]['fails']++;
                    echo "Failed for device ID: ". $device->dev_id."\n";
                    continue;
                }
                 $result = (new CamService())->convertImageAndSave($jsonImg, $device);
                 $msg= $i++. "  Success: saved new image in to server with img ID: ".$jsonImg["alarm_id"] . " from device ID: ". $jsonImg["dev_id"]."\n";
                 echo $msg;
                if ($result) {
                    Camalarms::where('id', $img->id)->update(['in_process' => 0, 'processed' => 1, 'processed_at' => time()]);
                    $stat[$device->dev_id]['count']++;
                } else {
                    Camalarms::where('id', $img->id)->increment('process_fail');
                    Camalarms::where('id', $img->id)->update(['in_process' => 0]);
                    $stat[$device->dev_id]['fails']++;
                }
                sleep(rand(1, 5));
            }
        }
        return $stat;
    }


    public static function getAlarmMessagesAndWriteInDb()
    {
        $stat = [];
       // $ifRegisterd = (new CamService())->checkIsClientexists();
//        if (!(int)$ifRegisterd->result) {
//            Log::alert("Client existence checks  failed");
//            echo "Client existence checks  failed \n";
//        }
    //    $cams = (new CamService())->getUserDevicesParams();
        $cams = Cameras::where('is_cloudBased', 1)->get();

        foreach ($cams as $cam) {
            $alarmMsg = (new CamService())->getAlarmMessages($cam, 5, 0);
            $stat[] = Camalarms::writeJsonToDb($alarmMsg);
        }

        return $stat;
    }


}