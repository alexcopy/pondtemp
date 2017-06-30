<?php

namespace App\Console\Commands;

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
        $allAlarms= \App\Http\Models\Camalarms::all();
        foreach ($allAlarms as $alarm) {

        }
        $tc=\Carbon\Carbon::createFromFormat('Y-m-d h:i:s', $time=$alarm->alarm_time, 'Asia/Shanghai');
        dd($tc->timestamp);
    }
}
