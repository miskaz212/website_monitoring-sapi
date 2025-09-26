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
    protected function schedule(Schedule $schedule)
    {
        // Jalankan cleanup sensor data setiap hari jam 02:00
        $schedule->command('sensor:cleanup --days=7')
                 ->dailyAt('02:00')
                 ->withoutOverlapping()
                 ->runInBackground();
        
        // Alternatif: Jalankan setiap minggu (Minggu jam 02:00)
        // $schedule->command('sensor:cleanup --days=7')
        //          ->weeklyOn(0, '02:00')
        //          ->withoutOverlapping()
        //          ->runInBackground();
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