<?php

namespace App\Events;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Task implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $task;

    const STATUS_RUNNING = 1;
    const STATUS_ERROR = 2;
    const STATUS_FINISHED = 3;

    public function __construct($id, $task, $message, $username, $startDate, $endDate = null, $status = self::STATUS_RUNNING)
    {
        $this->task = [
            'id' => $id,
            'message' => $message,
            'task' => $task,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'status' => $status,
            'username' => $username,
        ];
    }

    public function broadcastOn()
    {
        return new PrivateChannel('task');
    }
}
