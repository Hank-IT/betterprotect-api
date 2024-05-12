<?php

namespace App\Services\Tasks\Actions;

use App\Services\Tasks\Models\Task;
use Carbon\Carbon;

class UpdateTask
{
    public function execute(Task $task, string $message, string $status, Carbon $endDate): void
    {
        $task->update([
            'message' => $message,
            'status' => $status,
            'endDate' => $endDate,
        ]);
    }
}
