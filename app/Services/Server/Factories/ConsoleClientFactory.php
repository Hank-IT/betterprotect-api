<?php

namespace App\Services\Server\Factories;

use HankIT\ConsoleAccess\Adapters\Contract\Credential;
use HankIT\ConsoleAccess\Adapters\SshAdapter\Adapter as SSHAdapter;
use HankIT\ConsoleAccess\ConsoleAccess;
use phpseclib3\Net\SSH2;

class ConsoleClientFactory
{
    public function make(
        string $host,
        int $port,
        string $username,
        Credential $credential,
        string $publicKey,
    ): ConsoleAccess {
        $connection = new SSH2($host, $port);

        $adapter = new SSHAdapter(
            $connection, $username, $credential, $publicKey
        );

        return new ConsoleAccess($adapter);
    }
}
