<?php


namespace App\Http\Services;


use App\Http\Models\Cameras;
use App\Http\Models\ProxyList;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;

class CamService
{

    public function conditionalResult($from_time, $to_time, $device, $qty = 10)
    {
        $device->from_time = $from_time;
        $device->to_time = $to_time;
        $device->max_count = $qty;
        $server = env('CAM_ALRM_SERV', '');
        $url = $server . '/GetAlarmMsg/AlarmGetMessageListWithCondition?param=' . base64_encode(\GuzzleHttp\json_encode($device));

        try {
            return ProxyList::getCurlPage(ProxyList::getProxyForClient(), $url);

        } catch (\Exception $exception) {
            Log::critical('Didn\'t get valid JSON from conditionalResult server for dev_id=' . $device->dev_id .
                ' and time interval starts at: '
                . $from_time . ' and ending at: ' . $to_time . ' with message: ' . ' MSG: ' . $exception->getMessage());
            return '{"result":0}';
        }

    }

    public function processImage($img, $device)
    {
        $device->alarm_id = $img->alarm_id;
        $device->version = 2;
        $device->version = 2;
        $ports = ['8888', '8889'];
        $url = 'http://' . $img->ip . ':' . $ports[0] . '/GetAlarmMsg/AlarmGetPictureByID?param=' . base64_encode(\GuzzleHttp\json_encode($device));
        try {
            $page = ProxyList::getCurlPage(ProxyList::getProxyForClient(), $url);
            if (preg_match('~(?P<res>\{"result".+)~msi', $page, $mtch)) {
                $page = trim($mtch['res']);
            }
            return \GuzzleHttp\json_decode($page, true);
        } catch (\Exception $exception) {
            $msg = 'Didn\'t get valid JSON from image server for img_id=' . $img->alarm_id . ' and dev_id: '
                . $img->dev_id . ' with message: ' . '  MSG ' . $exception->getMessage();
            echo $msg;
            Log::critical($msg);
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
            if (!file_exists($imageName)) {
                $imageSave = imagejpeg($source, $imageName, 100);
            } else {
                $imageSave = true;
            }
            imagedestroy($source);
            return $imageSave;

        } catch (\Exception $exception) {
            Log::critical('Get an Error in saving IMAGE to hard drive' .
                $path . $jsonImage['dev_id'] . "_"
                . $jsonImage['alarm_id'] . "_" . time()
                . ".jpg" . ' with message: ' . '  MSG ' . $exception->getMessage() .
                'Stack trace:' . "\n"
                . '#0' . $jsonImage['alarm_image'] . "\n"
                . '#1 JsonObj' . \GuzzleHttp\json_encode($jsonImage)
            );
            return false;
        }
    }

    public function getUserDevicesParams()
    {
        $res = [];
        $cams = Cameras::where('is_cloudBased', 1)->get();
        foreach ($cams as $cam) {
            if (!$cam) continue;
            $res[] = (object)[
                'username' => $cam->login,
                'password' => $cam->password,
                'dev_id' => $cam->cam_id,
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
            'phone_type' => (int)env('CAM_PHONE_TYPE', ''),
            'phone_num' => 'unknow',
            'apikey' => env('CAM_APIKEY', ''),
            'secretkey' => env('CAM_SECRETKEY', ''),
            'channel_id' => (int)env('CAM_CHANNEL_ID', ''),
            'user_id' => 'unknow',
            'vibrate' => 0,
            'sound' => 0,
            'sound_file' => 'alarm.mp3',
            'recv_msg_pri' => 0,
            'sys_lan' => 'en',
        ];
    }

    /**
     * @return mixed|object
     */
    public function checkIsClientexists()
    {
        $server = env('CAM_EXIST_SERV', '');
        $url = $server . '/GetAlarmMsg/XGPhoneClientRegistered?param='
            . \GuzzleHttp\json_encode($this->getCheckParams());
        try {
            $page = (new Client())->request('GET', $url,
                [
                    'headers' => [
                        'User-Agent' => 'Dalvik/2.1.0 (Linux; U; Android 6.0.1; HTC Desire 530 Build/MMB29M)'
                    ]
                ])->getBody()->getContents();
            return \GuzzleHttp\json_decode($page);
        } catch (\Exception $exception) {
            Log::critical('client existence check failed' . '  Msg:  ' . $exception->getMessage());
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
            $page = ProxyList::getCurlPage(ProxyList::getProxyForClient(), $url);
            return $page;

        } catch (\Exception $exception) {
            Log::critical('Something Wrong With Getting Alarm messages' . '  MSG: ' . $exception->getMessage());
            return \GuzzleHttp\json_encode(['result' => 0]);
        }

    }
}