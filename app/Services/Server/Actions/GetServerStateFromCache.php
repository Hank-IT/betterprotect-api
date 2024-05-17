<?php

namespace App\Services\Server\Actions;

use App\Services\Server\dtos\ServerState;
use Illuminate\Support\Facades\Cache;

class GetServerStateFromCache
{
    public function __construct(
        protected GetServerStateCacheKeyForServer $getServerStateCacheKeyForServer,
    ) {}

    public function execute(string $hostname): ServerState
    {
        return Cache::get($this->getServerStateCacheKeyForServer->execute($hostname));
    }
}
