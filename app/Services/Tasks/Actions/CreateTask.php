<?php

namespace App\Services\Tasks\Actions;

use App\Services\Tasks\Models\Task;

class CreateTask
{
    public function execute(string $message, string $task, string $username): Task
    {
        return Task::create([
            'message' => $message,
            'task' => $task,
            'username' => $username,
        ]);
    }
}
