<?php

namespace App\Services\Server\Checks;

use App\Services\Server\Contracts\ServerMonitoringCheck;
use App\Services\Server\Factories\DatabaseFactory;
use App\Services\Server\Models\Server;

class PostfixDatabaseAvailable implements ServerMonitoringCheck
{
    public function __construct(protected DatabaseFactory $databaseFactory) {}

    public function getState(Server $server): mixed
    {
        return $this->databaseFactory->make(
            'postfix', $server->getDatabaseDetails('postfix')
        )->available();
    }

    public function getKey(): string
    {
        return 'postfix-database-available';
    }
}