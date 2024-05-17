<?php

namespace App\Services\Server\dtos;

class ServerState
{
    public function __construct(protected array $state) {}

    public function getPostfixDatabaseAvailable(): bool
    {
        return $this->state['postfix-database-available'];
    }

    public function getLogDatabaseAvailable(): bool
    {
        return $this->state['log-database-available'];
    }

    public function getPostqueueExecutableState(): bool
    {
        return $this->state['postqueue-executable'];
    }

    public function getPostsuperExecutableState(): bool
    {
        return $this->state['postsuper-executable'];
    }

    public function getSudoExecutableState(): bool
    {
        return $this->state['sudo-executable'];
    }

    public function getSshConnectionState(): bool
    {
        return $this->state['ssh-connection'];
    }
}
