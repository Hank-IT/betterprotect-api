<?php

namespace Tests\Feature\Services\PostfixQueue\Actions;

use App\Services\PostfixQueue\Actions\FlushPostfixQueue;
use App\Services\Server\dtos\SSHDetails;
use Exception;
use Hamcrest\Core\IsInstanceOf;
use HankIT\ConsoleAccess\ConsoleAccess;
use Illuminate\Support\Str;
use Mockery;
use App\Services\Server\Actions\GetConsole;
use Mockery\MockInterface;
use Tests\TestCase;

class FlushPostfixQueueTest extends TestCase
{
    public function test_success()
    {
        $queueId = (string) Str::uuid();
        $output = fake()->word;

        $sshDetails = SSHDetails::factory()->make();

        $consoleClient = Mockery::mock(ConsoleAccess::class, function(MockInterface $mock) use($sshDetails, $queueId, $output) {
            $mock->shouldReceive('sudo')->withArgs([
                $sshDetails->getSudoCommand()
            ])->andReturn($mock);

            $mock->shouldReceive('bin')->withArgs([
                $sshDetails->getPostqueueCommand()
            ])->andReturn($mock);

            $mock->shouldReceive('param')->withArgs([
                '-f'
            ])->andReturn($mock);

            $mock->shouldReceive('exec')->andReturn($mock);

            $mock->shouldReceive('getExitStatus')->andReturn(0);

            $mock->shouldReceive('getOutput')->andReturn($output);
        });

        $this->mock(GetConsole::class, function(MockInterface $mock) use($sshDetails, $consoleClient) {
            $mock->shouldReceive('execute')->once()->withArgs([
                IsInstanceOf::anInstanceOf(SSHDetails::class)
            ])->andReturn($consoleClient);
        });

        $this->assertEquals(
            $output, app(FlushPostfixQueue::class)->execute($sshDetails, $queueId)
        );
    }

    public function test_exception()
    {
        $queueId = (string) Str::uuid();

        $this->expectException(Exception::class);

        $sshDetails = SSHDetails::factory()->make();

        $consoleClient = Mockery::mock(ConsoleAccess::class, function(MockInterface $mock) use($sshDetails, $queueId) {
            $mock->shouldReceive('sudo')->withArgs([
                $sshDetails->getSudoCommand()
            ])->andReturn($mock);

            $mock->shouldReceive('bin')->withArgs([
                $sshDetails->getPostqueueCommand()
            ])->andReturn($mock);

            $mock->shouldReceive('param')->withArgs([
                '-f'
            ])->andReturn($mock);

            $mock->shouldReceive('exec')->andReturn($mock);

            $mock->shouldReceive('getExitStatus')->andReturn(1);

            $mock->shouldReceive('getOutput')->never();
        });

        $this->mock(GetConsole::class, function(MockInterface $mock) use($sshDetails, $consoleClient) {
            $mock->shouldReceive('execute')->once()->withArgs([
                IsInstanceOf::anInstanceOf(SSHDetails::class)
            ])->andReturn($consoleClient);
        });

        app(FlushPostfixQueue::class)->execute($sshDetails, $queueId);
    }
}
