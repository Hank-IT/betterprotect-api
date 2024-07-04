<?php

namespace App\Services\Server\Actions;

use App\Services\Server\Contracts\ServerMonitoringCheck;
use App\Services\Server\dtos\ServerState;
use App\Services\Server\Models\Server;

class GetServerState
{
    /**
     * @param $checks ServerMonitoringCheck[]
     */
    public function execute(Server $server, array $checks): ServerState
    {
        $state = [];

        foreach ($checks as $check) {
            $instance = app($check);

            $state[$instance->getKey()] = $instance->getState($server);
        }

        return new ServerState($state);
    }
}
