<?php

namespace App\Services\PostfixQueue\Actions;

use App\Exceptions\ErrorException;

class DeleteMailFromPostfixQueue
{
    public function execute(string $queueId): string
    {
        // ToDo

        $console = $this->server->console()->access();

        $console->sudo($this->server->ssh_command_sudo)
            ->bin($this->server->ssh_command_postsuper)
            ->param('-d')
            ->param($queueId)
            ->exec();

        if ($console->getExitStatus() !== 0) {
            throw new ErrorException;
        }

        return $console->getOutput();
    }
}
