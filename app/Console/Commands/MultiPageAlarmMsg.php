<?php


namespace App\Console\Commands;


use App\Http\Controllers\ApiController;
use Illuminate\Console\Command;

class MultiPageAlarmMsg  extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alarm:mutipage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = ' Is emergency Command in case we missed results in DB, retrospectively checking alrts ';

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
        $res = ApiController::multiplePagedResult(24);
        print_r($res);
    }
}