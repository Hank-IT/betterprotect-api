<?php

namespace App\Services\Tasks\Events;

use App\Services\Tasks\Enums\TaskStatusEnum;
use Carbon\Carbon;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskFinished implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public string $id,
        public string $description,
        public Carbon $endedAt,
        public string $status = TaskStatusEnum::FINISHED->value,
    ) {}

    public function broadcastOn()
    {
        return new PrivateChannel('task');
    }

    public function broadcastAs(): string
    {
        return 'task.finished';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'ended_at' => $this->endedAt,
            'status' => $this->status,
        ];
    }
}
