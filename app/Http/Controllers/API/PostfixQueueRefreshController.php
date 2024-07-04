<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\PostfixQueue\Actions\RefreshPostfixQueueCacheForServer;
use App\Services\Server\Actions\GetServerStateFromCache;
use App\Services\Server\Models\Server;

class PostfixQueueRefreshController extends Controller
{
    public function __invoke(
        Server $server,
        GetServerStateFromCache $getServerStateFromCache,
        RefreshPostfixQueueCacheForServer $refreshPostfixQueueCacheForServer,
    ) {
        $state = $getServerStateFromCache->execute($server->hostname);

        if (! $state->getSshConnectionState()->getAvailable()) {
            return response(status: 500);
        }

        $refreshPostfixQueueCacheForServer->execute($server);

        return response(status: 200);
    }
}
