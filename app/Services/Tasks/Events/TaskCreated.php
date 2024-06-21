<?php

namespace App\Services\Tasks\Events;

use App\Services\Tasks\Enums\TaskStatusEnum;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public string $id,
        public string $task,
        public string $username,
        public string $status = TaskStatusEnum::QUEUED->value,
    ) {}

    public function broadcastOn()
    {
        return new PrivateChannel('task');
    }

    public function broadcastAs(): string
    {
        return 'task.created';
    }
}
