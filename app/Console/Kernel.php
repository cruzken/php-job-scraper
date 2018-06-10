<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\ScrapeAuthenticJob;
use App\Jobs\ScrapeLarajobsJob;
use App\Jobs\ScrapeFreelancermapJob;;
use App\Jobs\ScrapeIndeedJob;
use App\Jobs\ScrapeJobmoteJob;

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
        $schedule->job(new ScrapeLarajobsJob)->everyTenMinutes();
        $schedule->job(new ScrapeJobmoteJob)->everyTenMinutes();
        $schedule->job(new ScrapeIndeedJob)->everyTenMinutes();
        $schedule->job(new ScrapeFreelancermapJob)->everyTenMinutes();
        $schedule->job(new ScrapeAuthenticJob)->everyTenMinutes();
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
