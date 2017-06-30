<?php

namespace App\Console\Commands;

use App\Http\Models\Camalarms;
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

     $x='{"result":5,"value":[{"id":170817106,"alarm_msg":"Camera","alarm_time":"2017-7-1 0:37:36","has_position":false,"version_num":1,"alarm_image":"","alarm_type":200,"dev_id":32177699,"alarm_id":170817106,"alarm_level":400,"last_fresh_time":1498840654925,"image_id":0,"ip":"116.62.31.134"},{"id":170814838,"alarm_msg":"Camera","alarm_time":"2017-7-1 0:35:34","has_position":false,"version_num":1,"alarm_image":"","alarm_type":200,"dev_id":32177699,"alarm_id":170814838,"alarm_level":400,"last_fresh_time":1498840533254,"image_id":0,"ip":"116.62.31.134"},{"id":170812486,"alarm_msg":"Camera","alarm_time":"2017-7-1 0:33:31","has_position":false,"version_num":1,"alarm_image":"","alarm_type":200,"dev_id":32177699,"alarm_id":170812486,"alarm_level":400,"last_fresh_time":1498840410364,"image_id":0,"ip":"116.62.31.134"},{"id":170810493,"alarm_msg":"Camera","alarm_time":"2017-7-1 0:31:45","has_position":false,"version_num":1,"alarm_image":"","alarm_type":200,"dev_id":32177699,"alarm_id":170810493,"alarm_level":400,"last_fresh_time":1498840304099,"image_id":0,"ip":"116.62.31.134"},{"id":170806310,"alarm_msg":"Camera","alarm_time":"2017-7-1 0:28:6","has_position":false,"version_num":1,"alarm_image":"","alarm_type":200,"dev_id":32177699,"alarm_id":170806310,"alarm_level":400,"last_fresh_time":1498840084647,"image_id":0,"ip":"116.62.31.134"}]} ';
    Camalarms::writeJsonToDb($x);

    }
}
