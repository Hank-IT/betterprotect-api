<?php

namespace App\Services\Server\Actions;

use App\Services\PostfixQueue\Actions\DeletePostfixQueueFromCache;
use App\Services\Server\Models\Server;

class DeleteServer
{
    public function __construct(
        protected DeletePostfixQueueFromCache $deletePostfixQueueFromCache,
        protected DeleteServerStateFromCache $deleteServerStateFromCache,
    ) {}

    public function execute(Server $server): ?bool
    {
        $this->deleteServerStateFromCache->execute($server->hostname);
        $this->deleteServerStateFromCache->execute($server->hostname);

        return $server->delete();
    }
}
