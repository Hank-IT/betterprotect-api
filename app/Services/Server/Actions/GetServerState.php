<?php

namespace App\Services\Server\Actions;

use App\Services\Server\Contracts\ServerMonitoringCheck;
use App\Services\Server\dtos\ServerState;
use App\Services\Server\Factories\DatabaseFactory;
use App\Services\Server\Models\Server;

class GetServerState
{
    public function __construct(protected DatabaseFactory $databaseFactory) {}

    /**
     * @param $checks ServerMonitoringCheck[]
     */
    public function execute(Server $server, array $checks): ServerState
    {
        $state = [];

        foreach ($checks as $check) {
            $state[$check->getKey()] = $check->getState($server);
        }

        return new ServerState($state);
    }
}
