<?php

namespace App\Services\Server\Checks;

use App\Services\Server\Actions\GetConsoleForServer;
use App\Services\Server\Contracts\ServerMonitoringCheck;
use App\Services\Server\Models\Server;
use Illuminate\Support\Facades\Log;

class PostsuperExecutable implements ServerMonitoringCheck
{
    public function __construct(protected GetConsoleForServer $getConsoleForServer) {}


    public function getState(Server $server): bool
    {
        $console = $this->getConsoleForServer->execute($server);

        $console->sudo($server->ssh_command_sudo . ' -n')
            ->bin($server->ssh_command_postsuper)
            ->exec();

        Log::debug($console->getOutput());

        return $console->getExitStatus() === 0;

    }

    public function getKey(): string
    {
        return 'postqueue-executable';
    }
}
