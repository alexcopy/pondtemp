<?php


namespace App\Console\Commands;


use App\Http\Controllers\ApiController;
use Illuminate\Console\Command;

class GetAlarmPicsMsgFromCloud extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getalarm:cams';

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
        $stat = ApiController::getAlarmMessagesAndWriteInDb();
        print_r($stat);
    }


}
