<?php

namespace App\Services\Server\Checks;

use App\Services\Server\Contracts\ServerMonitoringCheck;
use App\Services\Server\Factories\DatabaseFactory;
use App\Services\Server\Models\Server;

class LogDatabaseAvailable implements ServerMonitoringCheck
{
    public function __construct(protected DatabaseFactory $databaseFactory) {}

    public function getState(Server $server): mixed
    {
        return $this->databaseFactory->make(
            'log', $server->getDatabaseDetails('log')
        )->available();
    }

    public function getKey(): string
    {
        return 'log-database-available';
    }
}
