<?php

namespace App\Services\Server\Contracts;

use App\Services\Server\Models\Server;

interface ServerMonitoringCheck
{
    public function getState(Server $server): mixed;

    public function getKey(): string;
}
