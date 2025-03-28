<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\LoadToInfor::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('infor:workcenter')->dailyAt('00:00');
        $schedule->command('infor:partnumber')->dailyAt('00:30');
        $schedule->command('infor:work-part')->dailyAt('02:00');
        $schedule->command('infor:production-plan')->twiceDaily(7, 19);
        // $schedule->command('infor:load-to-infor')->twiceDaily(8, 20);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
