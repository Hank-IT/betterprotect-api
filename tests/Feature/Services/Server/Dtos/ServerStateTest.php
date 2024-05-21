<?php

namespace Tests\Feature\Services\Server\Dtos;

use App\Services\Server\dtos\ServerState;
use App\Services\Server\dtos\ServerStateCheckResult;
use Tests\TestCase;

class ServerStateTest extends TestCase
{
    public function test()
    {
        $serverState = new ServerState([
            'postfix-database-available' => new ServerStateCheckResult(true),
            'log-database-available' => new ServerStateCheckResult(true),
            'postqueue-executable' => new ServerStateCheckResult(true),
            'postsuper-executable' => new ServerStateCheckResult(true),
            'sudo-executable' => new ServerStateCheckResult(true),
            'ssh-connection' => new ServerStateCheckResult(true),
        ]);

        $this->assertTrue($serverState->getPostfixDatabaseAvailable()->getAvailable());
        $this->assertTrue($serverState->getLogDatabaseAvailable()->getAvailable());
        $this->assertTrue($serverState->getPostqueueExecutableState()->getAvailable());
        $this->assertTrue($serverState->getPostsuperExecutableState()->getAvailable());
        $this->assertTrue($serverState->getSudoExecutableState()->getAvailable());
        $this->assertTrue($serverState->getSshConnectionState()->getAvailable());
    }
}
