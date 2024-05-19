<?php

namespace App\Services\Server\Actions;

use App\Services\Server\Factories\ConsoleClientFactory;
use App\Services\Server\Models\Server;
use HankIT\ConsoleAccess\Adapters\SshAdapter\Key;
use HankIT\ConsoleAccess\ConsoleAccess;

class GetConsoleForServer
{
    public function __construct(protected ConsoleClientFactory $consoleClientFactory) {}

    public function execute(Server $server): ConsoleAccess
    {
        return $this->consoleClientFactory->make(
            $server->hostname,
            22,
            $server->ssh_user,
            new Key($server->ssh_private_key),
            $server->ssh_public_key
        );
    }
}
