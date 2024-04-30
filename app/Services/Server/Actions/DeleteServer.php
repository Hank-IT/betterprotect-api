<?php

namespace App\Services\Server\Actions;

use App\Services\Server\Models\Server;

class DeleteServer
{
    public function execute(Server $server): ?bool
    {
        return $server->delete();
    }
}
