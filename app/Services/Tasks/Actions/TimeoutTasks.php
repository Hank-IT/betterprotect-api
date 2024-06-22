<?php

namespace App\Services\Tasks\Actions;

use App\Services\Tasks\Enums\TaskStatusEnum;
use App\Services\Tasks\Events\TaskFailed;
use App\Services\Tasks\Models\Task;
use Carbon\Carbon;
use App\Services\Tasks\Models\TaskProgress as EloquentTaskProgress;

class TimeoutTasks
{
    public function execute()
    {
        Task::query()
            ->where('started_at', '<=', Carbon::now()->subHours(2))
            ->whereNull('ended_at')
            ->get()
            ->each(function($task) {
                EloquentTaskProgress::create([
                    'task_id' => $task->getKey(),
                    'description' => 'Timeout',
                ]);

                TaskFailed::dispatch(
                    $task->getKey(),
                    $task->task,
                    'Task failed due to timeout.',
                    Carbon::now(),
                );
            });
    }
}
