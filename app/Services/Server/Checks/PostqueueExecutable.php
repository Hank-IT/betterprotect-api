<?php

namespace App\Services\Server\Checks;

use Exception;
use App\Services\Server\Actions\GetConsole;
use App\Services\Server\Contracts\ServerMonitoringCheck;
use App\Services\Server\dtos\ServerStateCheckResult;
use App\Services\Server\Models\Server;

class PostqueueExecutable implements ServerMonitoringCheck
{
    public function __construct(protected GetConsole $getConsole) {}

    public function getState(Server $server): ServerStateCheckResult
    {
        $sshDetails = $server->getSSHDetails();

        $console = $this->getConsole->execute($sshDetails);

        try {
            $console->sudo($sshDetails->getSudoCommand() . ' -n')
                ->bin($sshDetails->getPostqueueCommand())
                ->param('-j')
                ->exec();
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
        return 'postqueue-executable';
    }
}
