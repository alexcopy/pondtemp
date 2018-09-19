<?php

namespace App\Console\Commands;

use App\Http\Models\Cameras;
use Carbon\Carbon;
use function Couchbase\defaultDecoder;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RebootCamersIfOffline extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cam:reboot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reboot Camera if offline';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cams = Cameras::where('name', 'koridor')->get();
        foreach ($cams as $cam) {
            $filesPath = storage_path('ftp') . '/' . $cam->realpath . '/today';
            $modified = File::lastModified($filesPath);
            $difference = time() - $modified;
            if ($difference > 7200) {
                $this->restartCam($cam);
            }
        }
    }

    private function restartCam($cam)
    {

        $url = $cam->alarmServerUrl . ':' . $cam->port;
        $curlHeader = ' ';
        $timout=5;
        $params = http_build_query([
            'action' => 'cmd',
            'code' => '255',
            'value' => '255'
        ]);

        try {
            $options = [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($cam->login . ':' . $cam->password),
                    'Cache-Control' => 'no-cache',
                    'Referer' => $url . '/cmd_req.asp',
                    'Upgrade-Insecure-Requests' => '1',
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36',
                ],
            ];

            foreach ($options['headers'] as $key => $header) {
                $curlHeader .= "   -H '$key:  $header'";
            }
            $str = "curl --max-time $timout -X GET  '$url/cgi-bin/control.cgi?$params'" . $curlHeader;
            $exec = exec($str);
            Log::alert('The cam has been restarted at: '. Carbon::today()->toDateTimeString() . "   " . "the camname is  $cam->name");
            return $exec . ' - Done';
        } catch (\Exception $exception) {

            return 'FAIL -    ' . $exception->getMessage();
        }
    }


}
