<?php

namespace App\Services\Server\Checks;

use App\Services\Server\dtos\ServerStateCheckResult;
use Carbon\Carbon;
use Exception;
use App\Services\Server\Actions\GetConsole;
use App\Services\Server\Contracts\ServerMonitoringCheck;
use App\Services\Server\Models\Server;
use HankIT\ConsoleAccess\Exceptions\ConnectionNotPossibleException;
use HankIT\ConsoleAccess\Exceptions\PublicKeyMismatchException;
use phpseclib3\Exception\NoKeyLoadedException;

class SshConnection implements ServerMonitoringCheck
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
            $console->bin('test')->param('-e')->param('/dev/null')->exec();
        } catch (ConnectionNotPossibleException) {
            return new ServerStateCheckResult(
                false,
                Carbon::now(),
                sprintf('Error: Connection not possible, Details: %s', $console->getOutput())
            );
        } catch (PublicKeyMismatchException) {
            return new ServerStateCheckResult(
                false,
                Carbon::now(),
                sprintf(
                    'Error: Public key mismatch, Key: [%s], Details: %s',
                    $console->getAdapter()->getServerPublicHostKey(),
                    $console->getOutput()
                )
            );
        } catch (Exception $exception) {
            return new ServerStateCheckResult(
                false,
                Carbon::now(),
                sprintf('Error: %s, Details: %s', $exception->getMessage(), $console->getOutput())
            );
        }

        return new ServerStateCheckResult(true, Carbon::now());
    }

    public function getKey(): string
    {
        return 'ssh-connection';
    }
}
