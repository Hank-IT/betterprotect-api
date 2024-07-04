<?php

namespace App\Services\Server\Checks;

use App\Services\Server\dtos\ServerStateCheckResult;
use Carbon\Carbon;
use Exception;
use App\Services\Server\Actions\GetConsole;
use App\Services\Server\Contracts\ServerMonitoringCheck;
use App\Services\Server\Models\Server;
use phpseclib3\Exception\NoKeyLoadedException;

class SudoExecutable implements ServerMonitoringCheck
{
    public function __construct(protected GetConsole $getConsole) {}

    public function getState(Server $server): ServerStateCheckResult
    {
        $sshDetails = $server->getSSHDetails();

        try {
            $console = $this->getConsole->execute($sshDetails);
        } catch (NoKeyLoadedException $exception) {
            return new ServerStateCheckResult(
                false,
                Carbon::now(),
                sprintf(
                    'Error: Unable to read private key, Details: %s',
                    $exception->getMessage()
                )
            );
        } catch (Exception $exception) {
            return new ServerStateCheckResult(
                false,
                Carbon::now(),
                sprintf('Error: Unable to get ssh connection., Details: %s', $exception->getMessage())
            );
        }

        try {
            $console->bin($sshDetails->getSudoCommand() . ' -h')->exec();
        } catch(Exception $exception) {
            return new ServerStateCheckResult(
                false,
                Carbon::now(),
                sprintf('Error: %s, Details: %s', $exception->getMessage(), $console->getOutput())
            );
        }

        return new ServerStateCheckResult(
            $console->getExitStatus() === 0,
            Carbon::now(),
            sprintf('Details: %s', $console->getOutput())
        );
    }

    public function getKey(): string
    {
        return 'sudo-executable';
    }
}
