<?php

namespace Tests\Feature\Services\Server\Jobs;

use App\Services\Server\Actions\StoreServerStateInCache;
use App\Services\Server\Events\ServerMonitored;
use Carbon\Carbon;
use Hamcrest\Core\IsInstanceOf;
use Mockery;
use App\Services\Server\Actions\GetServerState;
use App\Services\Server\dtos\ServerState;
use App\Services\Server\dtos\ServerStateCheckResult;
use App\Services\Server\Jobs\ServerMonitoring;
use App\Services\Server\Models\Server;
use Illuminate\Support\Facades\App;
use Mockery\MockInterface;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ServerMonitoringTest extends TestCase
{
    public function test()
    {
        Event::fake();

        Server::truncate();

        $job = new ServerMonitoring();

        $server = Server::factory()->create();

        $state = new ServerState([
            'postfix-database-available' => new ServerStateCheckResult(true, Carbon::now()),
            'log-database-available' => new ServerStateCheckResult(true, Carbon::now()),
            'postqueue-executable' => new ServerStateCheckResult(true, Carbon::now()),
            'postsuper-executable' => new ServerStateCheckResult(true, Carbon::now()),
            'sudo-executable' => new ServerStateCheckResult(true, Carbon::now()),
            'ssh-connection' => new ServerStateCheckResult(true, Carbon::now()),
        ]);

        $this->mock(GetServerState::class, function(MockInterface $mock) use($server, $state) {
            $mock->shouldReceive('execute')->once()->withArgs([
                Mockery::on(function($arg) use($server) {
                    return $arg->getKey() === $server->getKey();
                }),
                [
                    \App\Services\Server\Checks\LogDatabaseAvailable::class,
                    \App\Services\Server\Checks\PostfixDatabaseAvailable::class,
                    \App\Services\Server\Checks\SshConnection::class,
                    \App\Services\Server\Checks\SudoExecutable::class,
                    \App\Services\Server\Checks\PostsuperExecutable::class,
                    \App\Services\Server\Checks\PostqueueExecutable::class,
                ]
            ])->andReturn($state);
        });

        $this->mock(StoreServerStateInCache::class, function(MockInterface $mock) use($server, $state) {
            $mock->shouldReceive('execute')->once()->withArgs([
                $server->hostname,
                IsInstanceOf::anInstanceOf(ServerState::class),
            ]);
        });

        App::call([$job, 'handle']);

        Event::assertDispatched(ServerMonitored::class);
    }
}
