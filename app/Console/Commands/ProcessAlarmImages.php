<?php


namespace App\Console\Commands;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\CamApiController;
use Illuminate\Console\Command;

class ProcessAlarmImages extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alarm:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process images from camalarms table if success change status and download image to HD ';

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
         $res=CamApiController::processAlarmMessages();
         print_r($res);
    }
}