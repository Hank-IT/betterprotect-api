<?php

namespace App\Services\PostfixQueue\Actions;

use App\Exceptions\ErrorException;
use App\Services\Server\Actions\GetConsole;
use App\Services\Server\dtos\SSHDetails;

class FlushPostfixQueue
{
    public function __construct(protected GetConsole $getConsole) {}

    public function execute(SSHDetails $sshDetails): string
    {
        $console = $this->getConsole->execute($sshDetails);

        $console->sudo($sshDetails->getSudoCommand())
            ->bin($sshDetails->getPostqueueCommand())
            ->param('-f')
            ->exec();

        if ($console->getExitStatus() !== 0) {
            throw new ErrorException;
        }

        return $console->getOutput();
    }
}
