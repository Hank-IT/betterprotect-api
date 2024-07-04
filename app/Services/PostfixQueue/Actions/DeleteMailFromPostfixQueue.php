<?php

namespace App\Services\PostfixQueue\Actions;

use App\Services\Server\dtos\SSHDetails;
use Exception;
use App\Services\Server\Actions\GetConsole;

class DeleteMailFromPostfixQueue
{
    public function __construct(protected GetConsole $getConsole) {}

    public function execute(SSHDetails $sshDetails, string $queueId): string
    {
        $console = $this->getConsole->execute($sshDetails);

        $console->sudo($sshDetails->getSudoCommand())
            ->bin($sshDetails->getPostsuperCommand())
            ->param('-d')
            ->param($queueId)
            ->exec();

        if ($console->getExitStatus() !== 0) {
            throw new Exception;
        }

        return $console->getOutput();
    }
}
