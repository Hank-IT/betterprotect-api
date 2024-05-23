<?php

namespace App\Services\Tasks\Events;

use App\Services\Tasks\Models\Task;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public string $id,
        public string $task,
        public string $username,
        public string $status = Task::STATUS_QUEUED,
    ) {}

    public function broadcastOn()
    {
        return new PrivateChannel('task');
    }
}
