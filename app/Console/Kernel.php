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
        \App\Console\Commands\TaskCleanCommand::class,
        \App\Console\Commands\CreateUser::class,
        \App\Console\Commands\MigrateCheckCommand::class,
        \App\Console\Commands\InstallPolicy::class,
        \App\Console\Commands\StoreTransportRule::class,
        \App\Console\Commands\CleanTransportRulesByDataSource::class,
        \App\Console\Commands\QueryLdapDirectory::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('geoip:update')->weeklyOn(3, '8:00');
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
