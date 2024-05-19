<?php

namespace App\Services\PostfixQueue\Actions;

use Exception;
use App\Services\Server\Actions\GetConsoleForServer;
use App\Services\Server\Models\Server;

class DeleteMailFromPostfixQueue
{
    public function __construct(protected GetConsoleForServer $getConsoleForServer) {}

    public function execute(Server $server, string $queueId): string
    {
        $console = $this->getConsoleForServer->execute($server);

        $console->sudo($server->ssh_command_sudo)
            ->bin($server->ssh_command_postsuper)
            ->param('-d')
            ->param($queueId)
            ->exec();

        if ($console->getExitStatus() !== 0) {
            throw new Exception;
        }

        return $console->getOutput();
    }
}
