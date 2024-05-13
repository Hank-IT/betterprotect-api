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
        \App\Services\Tasks\Commands\TaskCleanCommand::class,
        \App\Services\Authentication\Commands\CreateUser::class,
        \App\Services\Server\Commands\MigrateCheckCommand::class,
        \App\Services\BetterprotectPolicy\Commands\InstallPolicy::class,
        \App\Services\Transport\Commands\StoreTransportRule::class,
        \App\Services\Transport\Commands\CleanTransportRulesByDataSource::class,
        \App\Services\Recipients\Commands\QueryLdapDirectory::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

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
