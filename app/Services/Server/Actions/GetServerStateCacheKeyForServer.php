<?php

namespace App\Services\Server\Actions;

class GetServerStateCacheKeyForServer
{
    public function execute(string $hostname): string
    {
        return sprintf('server-state-%s', $hostname);
    }
}
