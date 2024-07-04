<?php

namespace App\Services\PostfixQueue\Actions;

use App\Services\Server\Models\Server;

class RefreshPostfixQueueCacheForServer
{
    public function __construct(
        protected StorePostfixQueueInCache $storePostfixQueueInCache,
        protected GetPostfixQueueEntriesFromServer $getPostfixQueueEntriesFromServer,
    ) {}

    public function execute(Server $server): void
    {
        $this->storePostfixQueueInCache->execute(
            $server->hostname, $this->getPostfixQueueEntriesFromServer->execute($server->getSSHDetails())
        );
    }
}
