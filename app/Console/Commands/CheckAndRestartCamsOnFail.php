<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


class CheckAndRestartCamsOnFail extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:cams';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all Cams state and restart in case cam hasnt sent files over FTP for long time ';

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
     * Play Days are: Tuesday, Friday
     */
    public function handle()
    {

    }



}
