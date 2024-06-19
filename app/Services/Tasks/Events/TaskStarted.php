<?php

namespace App\Services\Tasks\Events;

use App\Services\Tasks\Enums\TaskStatusEnum;
use Carbon\Carbon;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskStarted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public string $id,
        public Carbon $startedAt,
        public string $status = TaskStatusEnum::RUNNING->value,
    ) {}

    public function broadcastOn()
    {
        return new PrivateChannel('task');
    }

    public function broadcastAs(): string
    {
        return 'task.started';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->id,
            'started_at' => $this->startedAt,
            'status' => $this->status,
        ];
    }
}
