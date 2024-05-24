<?php

namespace App\Services\Tasks\Listeners;

use App\Services\Tasks\Enums\TaskStatusEnum;
use App\Services\Tasks\Events\TaskFailed;
use App\Services\Tasks\Events\TaskProgress;
use App\Services\Tasks\Models\Task as EloquentTask;

class TransitionTaskToFailed
{
    public function handle(TaskFailed $event): void
    {
        $task = EloquentTask::query()->findOrFail($event->id);

        $task->update([
            'ended_at' => $event->endedAt,
            'status' => TaskStatusEnum::ERROR->value,
        ]);

        TaskProgress::dispatch(
            $event->id,
            $event->description,
        );
    }
}
