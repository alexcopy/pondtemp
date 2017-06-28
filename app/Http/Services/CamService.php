<?php


namespace App\Http\Services;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;
use Monolog\Logger;

class CamService
{

    protected $params;

    public function __construct()
    {
        $this->params = [
            'defaults' => [
                'headers' => [
                    'User-Agent' => 'Dalvik/2.1.0 (Linux; U; Android 6.0.1; HTC Desire 530 Build/MMB29M)',
                    'Connection' => 'Keep-Alive',
                    'Accept-Encoding' => 'gzip'
                ]
            ]
        ];
    }


    public function conditionalResult($from_time, $to_time, $device, $qty = 10)
    {
        $device->from_time = $from_time;
        $device->to_time = $to_time;
        $device->max_count = $qty;
        $server = env('CAM_ALRM_SERV', '');
        $url = $server . '/GetAlarmMsg/AlarmGetMessageListWithCondition?param=' . base64_encode(\GuzzleHttp\json_encode($device));

        try {

            return (new Client($this->params))->request('GET', $url)->getBody()->getContents();

        } catch (\Exception $exception) {
            (new Logger('Didn\'t get valid JSON from conditionalResult server for dev_id=' . $device->dev_id .
                ' and time interval starts at: '
                . $from_time . ' and ending at: ' . $to_time . ' with message: '))
                ->addCritical($exception->getMessage());
            return ['result' => 0];
        }

    }

    public function processImage($img, $device)
    {
        $device->alarm_id = $img->alarm_id;
        $device->version = 2;
        $device->version = 2;
        $url = 'http://' . $img->ip . ':8888/GetAlarmMsg/AlarmGetPictureByID?param=' . base64_encode(\GuzzleHttp\json_encode($device));

        try {
            $page = (new Client($this->params))->request('GET', $url)->getBody()->getContents();
            return \GuzzleHttp\json_decode($page, true);
        } catch (\Exception $exception) {
            (new Logger('Didn\'t get valid JSON from image server for img_id=' . $img->alarm_id . ' and dev_id: '
                . $img->dev_id . ' with message: '))
                ->addCritical($exception->getMessage());
            return ['result' => 0];
        }
    }

    public function convertImageAndSave($jsonImage, $device)
    {
        $path = storage_path('ftp/' . $device->dev_id . '/today/');
        try {
            if (!file_exists($path)) {
                File::makeDirectory($path, 0777, true);
            }
            $imageData = base64_decode($jsonImage['alarm_image']);
            $source = imagecreatefromstring($imageData);
            $imageName = $path . $device->dev_id . "_" . $jsonImage['alarm_id'] . "_" . time() . ".jpg";
            $imageSave = imagejpeg($source, $imageName, 100);
            imagedestroy($source);
            return $imageSave;

        } catch (\Exception $exception) {
            (new Logger('Get an Error in saving IMAGE to hard drive' .
                $path . $jsonImage['dev_id'] . "_"
                . $jsonImage['alarm_id'] . "_" . time()
                . ".jpg" . ' with message: '))
                ->addCritical($exception->getMessage());
            return false;
        }
    }

    public function getUserDevicesParams()
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

    /**
     * @return object
     */
    private function getCheckParams()
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

    /**
     * @return mixed|object
     */
    public function checkIsClientexists()
    {
        $server = env('CAM_ALRM_SERV', '');
        $url = $server . '/GetAlarmMsg/XGPhoneClientRegistered?param='
            . \GuzzleHttp\json_encode($this->getCheckParams());

        try {
            $page = (new Client($this->params))->request('GET', $url)->getBody()->getContents();
            return \GuzzleHttp\json_decode($page);
        } catch (\Exception $exception) {
            (new Logger('client existence check failed'))->addCritical($exception->getMessage());
            return (object)['result' => 0];
        }

    }

    public function getAlarmMessages($devObject, $max_count = 1, $last_fresh_time = 0)
    {
        $server = env('CAM_ALRM_SERV', '');
        $devObject->max_count = $max_count;
        $devObject->last_fresh_time = $last_fresh_time;

        $url = $server . '/GetAlarmMsg/AlarmSelectServletPicture?param='
            . \GuzzleHttp\json_encode($devObject);


        try {
            $page = (new Client($this->params))->request('GET', $url)->getBody()->getContents();
            return $page;

        } catch (\Exception $exception) {
            (new Logger('client existence check failed'))->addCritical($exception->getMessage());
            return (object)['result' => 0];
        }

    }
}