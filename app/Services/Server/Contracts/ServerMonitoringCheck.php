<?php

namespace App\Services\Server\Contracts;

use App\Services\Server\dtos\ServerStateCheckResult;
use App\Services\Server\Models\Server;

interface ServerMonitoringCheck
{
    public function getState(Server $server): ServerStateCheckResult;

    public function getKey(): string;
}
