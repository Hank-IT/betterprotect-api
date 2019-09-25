<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Task;
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
    public function handle()
    {
        Task::where('startDate', '<=', Carbon::now()->subHour(2))
            ->whereNull('endDate')
            ->get()
            ->each(function($task) {
                $task->update(['message' => 'Timeout', 'status' => Task::STATUS_ERROR]);
        });
    }
}
