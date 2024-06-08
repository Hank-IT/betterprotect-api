<?php

namespace App\Services\Server\Checks;

use App\Services\Server\Contracts\ServerMonitoringCheck;
use App\Services\Server\dtos\ServerStateCheckResult;
use App\Services\Server\Factories\DatabaseFactory;
use App\Services\Server\Models\Server;
use Carbon\Carbon;

class LogDatabaseAvailable implements ServerMonitoringCheck
{
    public function __construct(protected DatabaseFactory $databaseFactory) {}

    public function getState(Server $server): ServerStateCheckResult
    {
        $state = $this->databaseFactory->make(
            'log', $server->getDatabaseDetails('log')
        )->available();

        return new ServerStateCheckResult($state, Carbon::now(), 'Error: Log database is unavailable.');
    }

    public function getKey(): string
    {
        return 'log-database-available';
    }
}
