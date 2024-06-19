<?php

namespace App\Services\Tasks\Listeners;

use App\Services\Tasks\Models\Task as EloquentTask;
use App\Services\Tasks\Events\TaskCreated;
use App\Services\Tasks\Events\TaskProgress;

class CreateTask
{
    public function handle(TaskCreated $event): void
    {
        EloquentTask::create([
            'id' => $event->id,
            'task' => $event->task,
            'username' => $event->username,
            'status' => $event->status,
        ]);
    }
}
