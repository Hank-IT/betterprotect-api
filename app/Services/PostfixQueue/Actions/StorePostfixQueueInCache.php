<?php

namespace App\Services\PostfixQueue\Actions;

use Illuminate\Support\Facades\Cache;

class StorePostfixQueueInCache
{
    public function __construct(protected GetPostfixQueueCacheKeyForServer $getPostfixQueueCacheKeyForServer) {}

    public function execute(string $hostname, array $entries): void
    {
        Cache::forever($this->getPostfixQueueCacheKeyForServer->execute($hostname), $entries);
    }
}
