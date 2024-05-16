<?php

namespace App\Services\PostfixQueue\Actions;

use App\Services\PostfixQueue\PostfixQueue;
use App\Services\Server\Models\Server;

class GetPostfixQueueForServer
{
    public function execute(Server $server): array
    {
        // ToDo: Create ssh adapter
        $adapter = null;

        return new PostfixQueue($adapter);


        // ToDo: Update server_states table, if not reachable
        // ToDo: Update server_states table with mails in queue count
    }
}
