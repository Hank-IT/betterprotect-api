<?php

namespace App\Services\PostfixQueue\Actions;

use App\Services\PostfixQueue\PostfixQueue;
use App\Services\Server\Models\Server;

class GetPostfixQueueEntriesFromServer
{
    public function execute(Server $server): array
    {
        // ToDo: Create ssh adapter
        $adapter = null;

        return new PostfixQueue($adapter);
    }
}
