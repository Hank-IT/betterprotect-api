<?php

namespace App\Services\Server\dtos;

use Illuminate\Contracts\Support\Arrayable;

class ServerState implements Arrayable
{
    public function __construct(protected array $state) {}

    public function getPostfixDatabaseAvailable(): ServerStateCheckResult
    {
        return $this->state['postfix-database-available'];
    }

    public function getLogDatabaseAvailable(): ServerStateCheckResult
    {
        return $this->state['log-database-available'];
    }

    public function getPostqueueExecutableState(): ServerStateCheckResult
    {
        return $this->state['postqueue-executable'];
    }

    public function getPostsuperExecutableState(): ServerStateCheckResult
    {
        return $this->state['postsuper-executable'];
    }

    public function getSudoExecutableState(): ServerStateCheckResult
    {
        return $this->state['sudo-executable'];
    }

    public function getSshConnectionState(): ServerStateCheckResult
    {
        return $this->state['ssh-connection'];
    }

    public function toArray(): array
    {
        return $this->state;
    }
}
