<?php

namespace App\Services\Tasks\Listeners;

use App\Services\Tasks\Events\TaskProgress;
use App\Services\Tasks\Models\TaskProgress as EloquentTaskProgress;

class CreateProgress
{
    public function handle(TaskProgress $event): void
    {
        EloquentTaskProgress::create([
            'task_id' => $event->id,
            'description' => $event->description,
        ]);
    }
}
