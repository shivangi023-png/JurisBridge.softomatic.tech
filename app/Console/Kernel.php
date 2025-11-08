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
        //
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
        $schedule->command('autodetailsupdate:cron')
         ->timezone('Asia/Calcutta')
         ->at('9:30');
          $schedule->command('AlertMails:cron')
         ->timezone('Asia/Calcutta')
         ->at('09:00');
          $schedule->command('sendMailToSales:cron')
         ->timezone('Asia/Calcutta')
         ->at('09:00');
        //  $schedule->command('autodetailsupdate:cron')
        //  ->timezone('Asia/Calcutta')
        //  ->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
