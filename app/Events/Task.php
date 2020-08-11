<?php

namespace App\Events;

use App\Models\Task as TaskModel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class Task implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $task;

    public function __construct(TaskModel $task)
    {
        $task->refresh();

        $this->task = [
            'id' => $task->id,
            'message' => $task->message,
            'task' => $task->task,
            'startDate' => $task->startDate,
            'endDate' => $task->endDate,
            'status' => $task->status,
            'username' => $task->username,
        ];
    }

    public function broadcastOn()
    {
        return new PrivateChannel('task');
    }
}
