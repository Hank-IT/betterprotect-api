<?php

namespace App\Services\Tasks\Listeners;

use App\Services\Tasks\Events\TaskProgress;
use App\Services\Tasks\Events\TaskStarted;
use App\Services\Tasks\Models\Task as EloquentTask;

class TransitionTaskToStarted
{
    public function handle(TaskStarted $event): void
    {
        $task = EloquentTask::query()->findOrFail($event->id);

        $task->update([
            'started_at' => $task->started_at,
        ]);

        TaskProgress::dispatch(
            $event->id,
            'The task started.',
        );
    }
}