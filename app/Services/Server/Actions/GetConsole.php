<?php

namespace App\Services\Server\Actions;

use App\Services\Server\dtos\SSHDetails;
use App\Services\Server\Factories\ConsoleClientFactory;
use HankIT\ConsoleAccess\Adapters\SshAdapter\Key;
use HankIT\ConsoleAccess\ConsoleAccess;

class GetConsole
{
    public function __construct(protected ConsoleClientFactory $consoleClientFactory) {}

    public function execute(SSHDetails $sshDetails): ConsoleAccess
    {
        return $this->consoleClientFactory->make(
            $sshDetails->getHostname(),
            $sshDetails->getPort(),
            $sshDetails->getUser(),
            new Key($sshDetails->getPrivateKey()),
            $sshDetails->getPublicKey()
        );
    }
}
