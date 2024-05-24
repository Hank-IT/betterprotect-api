<?php

namespace App\Services\Tasks\Commands;

use App\Services\Tasks\Actions\TimeoutTasks;
use Illuminate\Console\Command;

class TaskCleanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark all tasks running longer than 120 minutes as failed.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(TimeoutTasks $timeoutTasks)
    {
        $timeoutTasks->execute();

        return 0;
    }
}
