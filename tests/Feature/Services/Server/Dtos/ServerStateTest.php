<?php

namespace Tests\Feature\Services\Server\Dtos;

use App\Services\Server\dtos\ServerState;
use App\Services\Server\dtos\ServerStateCheckResult;
use Carbon\Carbon;
use Tests\TestCase;

class ServerStateTest extends TestCase
{
    public function test()
    {
        $serverState = new ServerState([
            'postfix-database-available' => new ServerStateCheckResult(true, Carbon::now()),
            'log-database-available' => new ServerStateCheckResult(true, Carbon::now()),
            'postqueue-executable' => new ServerStateCheckResult(true, Carbon::now()),
            'postsuper-executable' => new ServerStateCheckResult(true, Carbon::now()),
            'sudo-executable' => new ServerStateCheckResult(true, Carbon::now()),
            'ssh-connection' => new ServerStateCheckResult(true, Carbon::now()),
        ]);

        $this->assertTrue($serverState->getPostfixDatabaseAvailable()->getAvailable());
        $this->assertTrue($serverState->getLogDatabaseAvailable()->getAvailable());
        $this->assertTrue($serverState->getPostqueueExecutableState()->getAvailable());
        $this->assertTrue($serverState->getPostsuperExecutableState()->getAvailable());
        $this->assertTrue($serverState->getSudoExecutableState()->getAvailable());
        $this->assertTrue($serverState->getSshConnectionState()->getAvailable());
    }
}
