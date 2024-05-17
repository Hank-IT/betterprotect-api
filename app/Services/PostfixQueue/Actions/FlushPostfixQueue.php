<?php

namespace App\Services\PostfixQueue\Actions;

use App\Exceptions\ErrorException;

class FlushPostfixQueue
{
    public function execute(): string
    {
        // ToDo

        $console = $this->server->console()->access();

        $console->sudo($this->server->ssh_command_sudo)
            ->bin($this->server->ssh_command_postqueue)
            ->param('-f')
            ->exec();

        if ($console->getExitStatus() !== 0) {
            throw new ErrorException;
        }

        return $console->getOutput();
    }
}
