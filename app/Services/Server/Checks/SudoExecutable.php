<?php

namespace App\Services\Server\Checks;

use App\Services\Server\Contracts\ServerMonitoringCheck;
use App\Services\Server\Models\Server;

class SudoExecutable implements ServerMonitoringCheck
{

    public function getState(Server $server): mixed
    {
        $this->console->bin($this->server->ssh_command_sudo . ' -h')->exec();

        Log::debug($this->console->getOutput());

        if ($this->console->getExitStatus() !== 0) {
            throw ValidationException::withMessages(['ssh_command_sudo' => 'Befehl konnte nicht ausgeführt werden.']);
        }
    }

    public function getKey(): string
    {
        return 'sudo-executable';
    }
}
