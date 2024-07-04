<?php

namespace App\Services\PostfixQueue\DataDriver;

use App\Services\Server\dtos\SSHDetails;
use Exception;
use App\Services\PostfixQueue\Contracts\DataDriver as DataDriverContract;
use App\Services\Server\Actions\GetConsole;

class SshDriver implements DataDriverContract
{
    public function __construct(protected SSHDetails $sshDetails) {}

    public function get(GetConsole $getConsole): string
    {
        $console = $getConsole->execute($this->sshDetails);

        $console->sudo($this->sshDetails->getSudoCommand())
            ->bin($this->sshDetails->getPostqueueCommand())
            ->param('-j')
            ->exec();

        if ($console->getExitStatus() !== 0) {
            throw new Exception;
        }

        return $console->getOutput();
    }
}
