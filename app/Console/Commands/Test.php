<?php

namespace App\Console\Commands;

use App\Http\Controllers\ApiController;
use App\Http\Models\Camalarms;
use App\Http\Models\MeterReadings;
use Carbon\Carbon;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Pusher\Laravel\Facades\Pusher;

class Test extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cam:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        $all = MeterReadings::all();
        foreach ($all as $item) {
            $item->cratedAt;


            $timestamp = $item->created_at->timestamp;
            $item->timestamp = $timestamp;
            $item->save();

        }


    }

}
