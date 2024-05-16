<?php

namespace App\Services\PostfixQueue\Actions;

use App\Services\PostfixQueue\Dtos\PostfixQueueEntry;
use App\Services\Server\Models\Server;
use Illuminate\Support\Facades\Cache;

class GetPostfixQueueEntriesFromCacheOrRetrieveAndCache
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
