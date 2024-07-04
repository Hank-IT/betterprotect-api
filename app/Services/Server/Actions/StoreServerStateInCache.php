<?php

namespace App\Services\Server\Actions;

use App\Services\Server\dtos\ServerState;
use Illuminate\Support\Facades\Cache;

class StoreServerStateInCache
{
    public function __construct(protected GetServerStateCacheKeyForServer $getServerStateCacheKeyForServer) {}

    public function execute(string $hostname, ServerState $serverState): void
    {
        Cache::forever($this->getServerStateCacheKeyForServer->execute($hostname), $serverState);
    }
}
