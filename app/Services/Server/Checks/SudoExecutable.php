<?php

namespace App\Services\Server\Checks;

use App\Services\Server\dtos\ServerStateCheckResult;
use Exception;
use App\Services\Server\Actions\GetConsole;
use App\Services\Server\Contracts\ServerMonitoringCheck;
use App\Services\Server\Models\Server;

class SudoExecutable implements ServerMonitoringCheck
{
    public function __construct(protected GetConsole $getConsole) {}

    public function getState(Server $server): ServerStateCheckResult
    {
        $sshDetails = $server->getSSHDetails();

        $console = $this->getConsole->execute($sshDetails);

        try {
            $console->bin($sshDetails->getSudoCommand() . ' -h')->exec();
        } catch(Exception $exception) {
            return new ServerStateCheckResult(
                false,
                sprintf('Error: %s, Details: %s', $exception->getMessage(), $console->getOutput())
            );
        }

        return new ServerStateCheckResult(
            $console->getExitStatus() === 0,
            sprintf('Details: %s', $console->getOutput())
        );
    }

    public function getKey(): string
    {
        return 'sudo-executable';
    }
}
