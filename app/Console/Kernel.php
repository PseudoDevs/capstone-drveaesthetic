<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Send appointment reminders every hour
        $schedule->command('appointments:send-reminders')
                 ->hourly()
                 ->withoutOverlapping()
                 ->runInBackground();

        // Send feedback requests daily at 9 AM
        $schedule->command('appointments:send-feedback-requests')
                 ->dailyAt('09:00')
                 ->withoutOverlapping();

        // Clean up old notifications weekly
        $schedule->command('notifications:cleanup')
                 ->weekly()
                 ->sundays()
                 ->at('02:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
