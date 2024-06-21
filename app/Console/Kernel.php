<?php

namespace App\Console;

use App\Services\PostfixQueue\Jobs\GetPostfixQueue;
use App\Services\Server\Jobs\ServerMonitoring;
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
        \App\Services\User\Commands\CreateUser::class,
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
        $schedule->job(GetPostfixQueue::class)->everyMinute();
        $schedule->job(ServerMonitoring::class)->everyMinute();
        $schedule->command('task:clean')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
