<?php

namespace App\Services\Tasks\Events;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskFinished
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public string $id,
        public string $description,
    ) {}

    public function broadcastOn()
    {
        return new PrivateChannel('task');
    }
}
