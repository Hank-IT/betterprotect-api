<?php

namespace App\Services\Server\Checks;

use App\Services\Server\Contracts\ServerMonitoringCheck;
use App\Services\Server\Models\Server;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PostsuperExecutable implements ServerMonitoringCheck
{

    public function getState(Server $server): mixed
    {
        $this->console->sudo($this->server->ssh_command_sudo . ' -n')->bin($this->server->ssh_command_postsuper)->exec();

        Log::debug($this->console->getOutput());

        if ($this->console->getExitStatus() !== 0) {
            throw ValidationException::withMessages(['ssh_command_postsuper' => 'Befehl konnte nicht ausgeführt werden.']);
        }
    }

    public function getKey(): string
    {
        return 'postqueue-executable';
    }
}
