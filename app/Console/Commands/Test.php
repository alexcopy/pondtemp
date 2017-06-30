<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

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

        $allAlarms = \App\Http\Models\Camalarms::all();
        foreach ($allAlarms as $alarm) {
            $timestampChina = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $alarm->alarm_time, 'Asia/Shanghai')->timestamp;
            $timestampLondon=Carbon::createFromFormat('Y-m-d H:i:s', $alarm->alarm_time, 'Europe/London')->timestamp;
            $fresh = (string)$alarm->last_fresh_time;
            $freshTime = substr($fresh, 0, 10);
            $diff = abs((int)$timestampLondon - (int)$freshTime);

            if ($diff > 4600) {
                $alarm->alarm_stamp = $timestampChina;
            } else {
                $alarm->alarm_stamp = $timestampLondon;
            }
            $alarm->save();
        }

    }
}
