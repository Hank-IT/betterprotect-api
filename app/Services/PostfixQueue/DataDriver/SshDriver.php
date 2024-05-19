<?php

namespace App\Services\PostfixQueue\DataDriver;

use Exception;
use App\Services\PostfixQueue\Contracts\DataDriver as DataDriverContract;
use App\Services\Server\Actions\GetConsoleForServer;
use App\Services\Server\Models\Server;

class SshDriver implements DataDriverContract
{
    public function __construct(protected Server $server) {}

    public function get(GetConsoleForServer $getConsoleForServer): string
    {
        $console = $getConsoleForServer->execute($this->server);

        $console->sudo($this->server->ssh_command_sudo)
            ->bin($this->server->ssh_command_postqueue)
            ->param('-j')
            ->exec();

        if ($console->getExitStatus() !== 0) {
            throw new Exception;
        }

        return $console->getOutput();
    }
}
