<?php

namespace Tests\Feature\Services\Server\Actions;

use App\Services\Server\Actions\GetServerState;
use App\Services\Server\Checks\LogDatabaseAvailable;
use App\Services\Server\dtos\ServerStateCheckResult;
use App\Services\Server\Models\Server;
use Mockery\MockInterface;
use Tests\TestCase;

class GetServerStateTest extends TestCase
{
    public function test()
    {
        $server = Server::factory()->create();

        $this->mock(LogDatabaseAvailable::class, function(MockInterface $mock) {
            $mock->shouldReceive('getState')->once()->andReturn(new ServerStateCheckResult(true));
            $mock->shouldReceive('getKey')->once()->andReturn('log-database-available');
        });

        $state = app(GetServerState::class)->execute($server, [
            LogDatabaseAvailable::class,
        ]);

        $this->assertTrue($state->getLogDatabaseAvailable()->getAvailable());
    }
}
