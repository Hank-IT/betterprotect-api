<?php

namespace App\Services\PostfixQueue\DataDriver;

use App\Exceptions\ErrorException;
use App\Services\PostfixQueue\Contracts\DataDriver as DataDriverContract;

class SshDriver implements DataDriverContract
{
    public function __construct()
    {

    }

    public function get(): string
    {
        // ToDo

        $console = $this->server->console()->access();

        $console->sudo($this->server->ssh_command_sudo)
            ->bin($this->server->ssh_command_postqueue)
            ->param('-j')
            ->exec();

        if ($console->getExitStatus() !== 0) {
            throw new ErrorException;
        }

        return $console->getOutput();
    }
}
