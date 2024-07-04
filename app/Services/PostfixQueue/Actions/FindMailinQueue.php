<?php

namespace App\Services\PostfixQueue\Actions;

use App\Services\PostfixQueue\Dtos\PostfixQueueEntry;
use App\Services\Server\Models\Server;

class FindMailinQueue
{
    public function __construct(protected GetPostfixQueueEntriesFromCache $getPostfixQueueEntriesFromCache) {}

    public function execute(Server $server, string $queueId): ?PostfixQueueEntry
    {
        $entries = $this->getPostfixQueueEntriesFromCache->execute($server->hostname);

        return collect($entries)->firstWhere(function(PostfixQueueEntry $entry) use($queueId) {
            return $entry->getQueueId() === $queueId;
        });
    }
}
