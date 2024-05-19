<?php

namespace App\Services\PostfixQueue\Actions;

use App\Exceptions\ErrorException;
use App\Services\Server\Actions\GetConsoleForServer;
use App\Services\Server\Models\Server;

class FlushPostfixQueue
{
    public function __construct(protected GetConsoleForServer $getConsoleForServer) {}

    public function execute(Server $server): string
    {
        $console = $this->getConsoleForServer->execute($server);

        $console->sudo($server->ssh_command_sudo)
            ->bin($server->ssh_command_postqueue)
            ->param('-f')
            ->exec();

        if ($console->getExitStatus() !== 0) {
            throw new ErrorException;
        }

        return $console->getOutput();
    }
}
