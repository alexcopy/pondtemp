<?php

namespace App\Console;

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
        MultiPageAlarmMsg::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('alarm:cams')->everyFiveMinutes();
        $schedule->command('alarm:process')->everyFiveMinutes();
        $schedule->command('alarm:mutipage')->dailyAt('03:00');
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
