<?php

namespace App\Console;

use App\Console\Commands\GetAlarmPicsMsgFromCloud;
use App\Console\Commands\ProcessAlarmImages;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        GetAlarmPicsMsgFromCloud::class,
        ProcessAlarmImages::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('migrate')->everyThirtyMinutes();
        $schedule->command('alarm:cams')->everyFiveMinutes();
        $schedule->command('alarm:process')->everyFiveMinutes();
        $schedule->command('clockwork:clean')->dailyAt('18:00');
    }
}
