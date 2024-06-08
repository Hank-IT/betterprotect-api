<?php

namespace App\Services\Server\Actions;

use Illuminate\Support\Facades\Cache;

class DeleteServerStateFromCache
{
    public function __construct(protected GetServerStateCacheKeyForServer $getServerStateCacheKeyForServer) {}

    public function execute(string $hostname): void
    {
        Cache::forget($this->getServerStateCacheKeyForServer->execute($hostname));
    }
}
