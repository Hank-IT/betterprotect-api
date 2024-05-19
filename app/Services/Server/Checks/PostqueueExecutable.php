<?php

namespace App\Services\Server\Checks;

use App\Services\Server\Actions\GetConsole;
use App\Services\Server\Contracts\ServerMonitoringCheck;
use App\Services\Server\Models\Server;
use Illuminate\Support\Facades\Log;

class PostqueueExecutable implements ServerMonitoringCheck
{
    public function __construct(protected GetConsole $getConsole) {}

    public function getState(Server $server): bool
    {
        $sshDetails = $server->getSSHDetails();

        $console = $this->getConsole->execute($sshDetails);

        $console->sudo($sshDetails->getSudoCommand() . ' -n')
            ->bin($sshDetails->getPostqueueCommand())
            ->param('-j')
            ->exec();

        Log::debug($console->getOutput());

        return $console->getExitStatus() === 0;
    }

    public function getKey(): string
    {
        return 'postqueue-executable';
    }
}
