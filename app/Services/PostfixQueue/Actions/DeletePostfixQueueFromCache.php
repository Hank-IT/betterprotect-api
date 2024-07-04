<?php

namespace App\Services\PostfixQueue\Actions;

use Illuminate\Support\Facades\Cache;

class DeletePostfixQueueFromCache
{
    public function __construct(protected GetPostfixQueueCacheKeyForServer $getPostfixQueueCacheKeyForServer) {}

    public function execute(string $hostname): void
    {
        Cache::forget($this->getPostfixQueueCacheKeyForServer->execute($hostname));
    }
}
