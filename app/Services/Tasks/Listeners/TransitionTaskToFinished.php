<?php

namespace App\Services\Tasks\Listeners;

use App\Services\Tasks\Events\TaskFinished;
use App\Services\Tasks\Events\TaskProgress;
use App\Services\Tasks\Models\Task as EloquentTask;

class TransitionTaskToFinished
{
    public function handle(TaskFinished $event): void
    {
        $task = EloquentTask::query()->findOrFail($event->id);

        $task->update([
            'ended_at' => $task->ended_at,
        ]);

        TaskProgress::dispatch(
            $event->id,
            $event->description,
        );
    }
}
