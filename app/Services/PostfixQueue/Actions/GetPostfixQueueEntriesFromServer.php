<?php

namespace App\Services\PostfixQueue\Actions;

use App\Services\PostfixQueue\DataDriver\SshDriver;
use App\Services\PostfixQueue\PostfixQueue;
use App\Services\Server\Models\Server;

class GetPostfixQueueEntriesFromServer
{
    public function execute(Server $server): array
    {
        return (new PostfixQueue(new SshDriver($server)))->get();
    }
}
