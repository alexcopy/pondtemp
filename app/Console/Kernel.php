<?php

namespace App\Console;

use App\Console\Commands\CreateDataBase;
use App\Console\Commands\RebootCamersIfOffline;
use App\Console\Commands\Test;
use App\Console\Commands\ZipCamFiles;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\GetAlarmPicsMsgFromCloud;
use App\Console\Commands\MultiPageAlarmMsg;
use App\Console\Commands\ProcessAlarmImages;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        GetAlarmPicsMsgFromCloud::class,
        ProcessAlarmImages::class,
        MultiPageAlarmMsg::class,
        ZipCamFiles::class,
        RebootCamersIfOffline::class,
        CreateDataBase::class,
        Test::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('alarm:cams')->everyFiveMinutes();
        $schedule->command('cam:reboot 3600')->everyThirtyMinutes()->between("03:00", "06:00");
        $schedule->command('cam:reboot 1800')->hourly()->between("7:00", "23:00");
//        $schedule->command('alarm:mutipage')->dailyAt('03:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
