<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    // protected function schedule(Schedule $schedule)
    // {
    //     // $schedule->command('inspire')->hourly();
    // }

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
    
    protected function schedule(Schedule $schedule)
    {
        // Check and initiate calls every minute
        $schedule->command('calls:check-and-initiate')->everyMinute();
        
        // Fetch call history every 5 minutes to update status
        $schedule->command('fetch:call-history')->everyMinute();

        
        // Process monthly billing daily at 10 AM
        $schedule->command('billing:monthly')->dailyAt('10:00');
    }


}
