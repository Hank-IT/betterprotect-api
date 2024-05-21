<?php

namespace App\Services\Server\Checks;

use App\Services\Server\dtos\ServerStateCheckResult;
use Exception;
use App\Services\Server\Actions\GetConsole;
use App\Services\Server\Contracts\ServerMonitoringCheck;
use App\Services\Server\Models\Server;
use HankIT\ConsoleAccess\Exceptions\ConnectionNotPossibleException;
use HankIT\ConsoleAccess\Exceptions\PublicKeyMismatchException;

class SshConnection implements ServerMonitoringCheck
{
    public function __construct(protected GetConsole $getConsole) {}

    public function getState(Server $server): ServerStateCheckResult
    {
        $sshDetails = $server->getSSHDetails();

        $console = $this->getConsole->execute($sshDetails);

        try {
            $console->bin('test')->param('-e')->param('/dev/null')->exec();
        } catch(ConnectionNotPossibleException) {
            return new ServerStateCheckResult(
                false,
                sprintf('Error: Connection not possible, Details: %s', $console->getOutput())
            );
        } catch(PublicKeyMismatchException) {
            return new ServerStateCheckResult(
                false,
                sprintf(
                    'Error: Public key mismatch, Key: [%s], Details: %s',
                    $console->getAdapter()->getServerPublicHostKey(),
                    $console->getOutput()
                )
            );
        } catch(Exception $exception) {
            return new ServerStateCheckResult(
                false,
                sprintf('Error: %s, Details: %s', $exception->getMessage(), $console->getOutput())
            );
        }

        return new ServerStateCheckResult(true);
    }

    public function getKey(): string
    {
        return 'ssh-connection';
    }
}
