<?php

namespace Services\PostfixQueue\Actions;

use App\Services\PostfixQueue\Actions\FlushPostfixQueue;
use Exception;
use HankIT\ConsoleAccess\ConsoleAccess;
use Illuminate\Support\Str;
use Mockery;
use App\Services\Server\Actions\GetConsoleForServer;
use App\Services\Server\Models\Server;
use Mockery\MockInterface;
use Tests\TestCase;

class FlushPostfixQueueTest extends TestCase
{
    public function test_success()
    {
        $queueId = (string) Str::uuid();
        $output = fake()->word;

        $server = Server::factory()->create();

        $consoleClient = Mockery::mock(ConsoleAccess::class, function(MockInterface $mock) use($server, $queueId, $output) {
            $mock->shouldReceive('sudo')->withArgs([
                $server->ssh_command_sudo
            ])->andReturn($mock);

            $mock->shouldReceive('bin')->withArgs([
                $server->ssh_command_postqueue
            ])->andReturn($mock);

            $mock->shouldReceive('param')->withArgs([
                '-f'
            ])->andReturn($mock);

            $mock->shouldReceive('exec')->andReturn($mock);

            $mock->shouldReceive('getExitStatus')->andReturn(0);

            $mock->shouldReceive('getOutput')->andReturn($output);
        });

        $this->mock(GetConsoleForServer::class, function(MockInterface $mock) use($server, $consoleClient) {
            $mock->shouldReceive('execute')->once()->withArgs([
                Mockery::on(function($arg) use($server) {
                    return $server->getKey() === $arg->getKey();
                })
            ])->andReturn($consoleClient);
        });

        $this->assertEquals(
            $output, app(FlushPostfixQueue::class)->execute($server, $queueId)
        );
    }

    public function test_exception()
    {
        $queueId = (string) Str::uuid();

        $this->expectException(Exception::class);

        $server = Server::factory()->create();

        $consoleClient = Mockery::mock(ConsoleAccess::class, function(MockInterface $mock) use($server, $queueId) {
            $mock->shouldReceive('sudo')->withArgs([
                $server->ssh_command_sudo
            ])->andReturn($mock);

            $mock->shouldReceive('bin')->withArgs([
                $server->ssh_command_postqueue
            ])->andReturn($mock);

            $mock->shouldReceive('param')->withArgs([
                '-f'
            ])->andReturn($mock);

            $mock->shouldReceive('exec')->andReturn($mock);

            $mock->shouldReceive('getExitStatus')->andReturn(1);

            $mock->shouldReceive('getOutput')->never();
        });

        $this->mock(GetConsoleForServer::class, function(MockInterface $mock) use($server, $consoleClient) {
            $mock->shouldReceive('execute')->once()->withArgs([
                Mockery::on(function($arg) use($server) {
                    return $server->getKey() === $arg->getKey();
                })
            ])->andReturn($consoleClient);
        });

        app(FlushPostfixQueue::class)->execute($server, $queueId);
    }
}
