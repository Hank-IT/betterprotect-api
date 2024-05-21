<?php

namespace Tests\Feature\Services\PostfixQueue\Jobs;

use App\Services\PostfixQueue\Actions\GetPostfixQueueEntriesFromServer;
use App\Services\PostfixQueue\Actions\StorePostfixQueueInCache;
use App\Services\Server\dtos\ServerStateCheckResult;
use App\Services\Server\dtos\SSHDetails;
use Hamcrest\Core\IsInstanceOf;
use Mockery;
use App\Services\PostfixQueue\Jobs\GetPostfixQueue;
use App\Services\Server\Actions\GetServerStateFromCache;
use App\Services\Server\dtos\ServerState;
use App\Services\Server\Models\Server;
use Illuminate\Support\Facades\App;
use Mockery\MockInterface;
use Tests\TestCase;

class GetPostfixQueueTest extends TestCase
{
    public function test_successful()
    {
        $server = Server::factory()->create();

        $rows = ['test'];

        $serverStateMock = Mockery::mock(ServerState::class, function(MockInterface $mock) {
            $mock->shouldReceive('getSshConnectionState')->once()->andReturn(new ServerStateCheckResult(true));
        });

        $this->mock(GetServerStateFromCache::class, function(MockInterface $mock) use($server, $serverStateMock) {
            $mock->shouldReceive('execute')->once()->withArgs([$server->hostname])->andReturn($serverStateMock);
        });

        $this->mock(GetPostfixQueueEntriesFromServer::class, function(MockInterface $mock) use($rows) {
            $mock->shouldReceive('execute')->once()->withArgs([
               IsInstanceOf::anInstanceOf(SSHDetails::class),
            ])->andReturn($rows);
        });

        $this->mock(StorePostfixQueueInCache::class, function(MockInterface $mock) use($rows, $server) {
            $mock->shouldReceive('execute')->once()->withArgs([
                $server->hostname,
                $rows,
            ]);
        });

        $job = new GetPostfixQueue;

        App::call([$job, 'handle']);
    }

    public function test_failure()
    {
        $server = Server::factory()->create();

        $rows = ['test'];

        $serverStateMock = Mockery::mock(ServerState::class, function(MockInterface $mock) {
            $mock->shouldReceive('getSshConnectionState')->once()->andReturn(new ServerStateCheckResult(false));
        });

        $this->mock(GetServerStateFromCache::class, function(MockInterface $mock) use($server, $serverStateMock) {
            $mock->shouldReceive('execute')->once()->withArgs([$server->hostname])->andReturn($serverStateMock);
        });

        $this->mock(GetPostfixQueueEntriesFromServer::class, function(MockInterface $mock) {
            $mock->shouldReceive('execute')->never();
        });

        $this->mock(StorePostfixQueueInCache::class, function(MockInterface $mock) {
            $mock->shouldReceive('execute')->never();
        });

        $job = new GetPostfixQueue;

        App::call([$job, 'handle']);
    }
}
