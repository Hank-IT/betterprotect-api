<?php

namespace Tests\Feature\Services\Server\Dtos;

use App\Services\Server\dtos\ServerState;
use Tests\TestCase;

class ServerStateTest extends TestCase
{
    public function test()
    {
        $serverState = new ServerState([
            'postfix-database-available' => true,
            'log-database-available' => true,
            'postqueue-executable' => true,
            'postsuper-executable' => true,
            'sudo-executable' => true,
            'ssh-connection' => true,
        ]);

        $this->assertTrue($serverState->getPostfixDatabaseAvailable());
        $this->assertTrue($serverState->getLogDatabaseAvailable());
        $this->assertTrue($serverState->getPostqueueExecutableState());
        $this->assertTrue($serverState->getPostsuperExecutableState());
        $this->assertTrue($serverState->getSudoExecutableState());
        $this->assertTrue($serverState->getSshConnectionState());
    }
}
