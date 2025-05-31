<?php

namespace App\Console;

use App\Console\Commands\SendCampaignCommand;
use App\Console\Commands\SendMailChat;
use App\Console\Commands\UpdateCompanyCountryIfNull;
use App\Console\Commands\UpdateJobCountryIfNull;
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
        SendCampaignCommand::class,
        SendMailChat::class,
        // UpdateJobCountryIfNull::class,
        // UpdateCompanyCountryIfNull::class,
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
        $schedule->command('jobpostexpiry:cron')->daily();
        $schedule->command('planexpiry:cron')->daily();
        $schedule->command('send:campaign')->everyMinute();
        $schedule->command('send:mail-chat')->hourly();
        // $schedule->command('update:country-if-null')->everyTwoMinutes();
        // $schedule->command('update:company-country-if-null')->everyFiveMinutes();
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
