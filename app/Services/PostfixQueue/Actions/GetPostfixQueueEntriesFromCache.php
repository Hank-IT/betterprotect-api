<?php

namespace App\Services\PostfixQueue\Actions;

use App\Services\PostfixQueue\Dtos\PostfixQueueEntry;
use Illuminate\Support\Facades\Cache;

class GetPostfixQueueEntriesFromCache
{
    public function __construct(
        protected GetPostfixQueueCacheKeyForServer $getPostfixQueueCacheKeyForServer,
    ) {}

    /**
     * @return PostfixQueueEntry[]
     */
    public function execute(string $hostname): array
    {
        return Cache::get($this->getPostfixQueueCacheKeyForServer->execute($hostname));
    }
}
