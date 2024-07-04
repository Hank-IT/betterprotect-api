<?php

namespace App\Services\PostfixQueue\Actions;

class GetPostfixQueueCacheKeyForServer
{
    public function execute(string $hostname): string
    {
        return sprintf('postfix-queue-content-%s', $hostname);
    }
}
