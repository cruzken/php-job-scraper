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
        $scrapeLog = 'storage/logs/scraper.txt';
            
        $schedule->command('scrape:authenticjobs')->everyMinute()->appendOutputTo($scrapeLog);
        $schedule->command('scrape:jobmote')->everyMinute()->appendOutputTo($scrapeLog);
        $schedule->command('scrape:larajobs')->everyMinute()->appendOutputTo($scrapeLog);
        $schedule->command('scrape:indeed')->everyMinute()->appendOutputTo($scrapeLog);
        $schedule->command('scrape:freelancermap')->everyMinute()->appendOutputTo($scrapeLog);
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
