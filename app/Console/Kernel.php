<?php

namespace App\Console;

use App\Console\Commands\SubscriptionReminderCommand;
use App\Http\Controllers\PreregistrationController;
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
        SubscriptionReminderCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $filePath = storage_path("taskslog\output.txt");

//         $schedule->command('inspire')->everyMinute();
        $schedule->exec('php artisan queue:work --stop-when-empty')->everyMinute()->emailOutputOnFailure('odejinmisa@newwavesecosystem.com');

        $schedule->call(function () {
            $er = new PreregistrationController();
            $er->checkReminder();
        })->dailyAt('06:00')->emailOutputOnFailure('odejinmisa@newwavesecosystem.com');

        $schedule->command('samji:subscriptionreminder')
            ->withoutOverlapping()
            ->dailyAt('05:30');

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
