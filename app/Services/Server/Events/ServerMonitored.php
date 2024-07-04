<?php

namespace App\Services\Server\Events;

use App\Services\Server\dtos\ServerState;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class ServerMonitored implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(protected ServerState $serverState) {}

    public function broadcastWith(): array
    {
        return $this->serverState->toArray();
    }

    public function broadcastAs(): string
    {
        return 'server.monitored';
    }

    public function broadcastOn()
    {
        return new PrivateChannel('monitoring');
    }
}
