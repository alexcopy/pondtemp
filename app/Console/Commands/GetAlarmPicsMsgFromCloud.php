<?php


namespace App\Console\Commands;


use App\Http\Controllers\ApiController;
use App\Http\Controllers\CamApiController;
use App\Http\Models\ProxyList;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetAlarmPicsMsgFromCloud extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alarm:cams';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Alarm Messages from Cloud for p2pcams  ';

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
     */
    public function handle()
    {
        if(Carbon::now()->hour==3) return null;
        $res = CamApiController::getAlarmMessagesAndWriteInDb();
        Log::info('Run get alarm messages schedule, got results: '.\GuzzleHttp\json_encode($res));
    }


}
