<?php

namespace Services\Server\Checks;

use App\Services\Server\Checks\SudoExecutable;
use Exception;
use App\Services\Server\Checks\PostqueueExecutable;
use App\Services\Server\Checks\PostsuperExecutable;
use App\Services\Server\dtos\SSHDetails;
use Hamcrest\Core\IsInstanceOf;
use HankIT\ConsoleAccess\ConsoleAccess;
use Mockery;
use App\Services\Server\Actions\GetConsole;
use App\Services\Server\Models\Server;
use Mockery\MockInterface;
use Tests\TestCase;

class SudoExecutableTest extends TestCase
{
    public function test_executable()
    {
        $server = Server::factory()->create();

        $sshDetails = $server->getSSHDetails();

        $consoleMock = Mockery::mock(ConsoleAccess::class, function (MockInterface $mock) use ($sshDetails) {
            $mock->shouldReceive('bin')->once()->withArgs([$sshDetails->getSudoCommand() . ' -h'])->andReturn($mock);
            $mock->shouldReceive('exec')->once()->andReturn($mock);
            $mock->shouldReceive('getOutput')->once()->andReturn('output');
            $mock->shouldReceive('getExitStatus')->once()->andReturn(0);
        });

        $this->mock(GetConsole::class, function(MockInterface $mock) use($sshDetails, $consoleMock) {
            $mock->shouldReceive('execute')->once()->withArgs([
                IsInstanceOf::anInstanceOf(SSHDetails::class)
            ])->andReturn($consoleMock);
        });

        $this->assertTrue(app(SudoExecutable::class)->getState($server)->getAvailable());
    }

    public function test_unavailable()
    {
        $server = Server::factory()->create();

        $sshDetails = $server->getSSHDetails();

        $consoleMock = Mockery::mock(ConsoleAccess::class, function (MockInterface $mock) use ($sshDetails) {
            $mock->shouldReceive('bin')->once()->withArgs([$sshDetails->getSudoCommand() . ' -h'])->andReturn($mock);
            $mock->shouldReceive('exec')->once()->andReturn($mock);
            $mock->shouldReceive('getOutput')->once()->andReturn('output');
            $mock->shouldReceive('getExitStatus')->once()->andReturn(1);
        });

        $this->mock(GetConsole::class, function(MockInterface $mock) use($server, $consoleMock) {
            $mock->shouldReceive('execute')->once()->withArgs([
                IsInstanceOf::anInstanceOf(SSHDetails::class),
            ])->andReturn($consoleMock);
        });

        $this->assertFalse(app(SudoExecutable::class)->getState($server)->getAvailable());
    }

    public function test_error()
    {
        $server = Server::factory()->create();

        $sshDetails = $server->getSSHDetails();

        $consoleMock = Mockery::mock(ConsoleAccess::class, function (MockInterface $mock) use ($sshDetails) {
            $mock->shouldReceive('bin')->once()->withArgs([$sshDetails->getSudoCommand() . ' -h'])->andReturn($mock);
            $mock->shouldReceive('exec')->once()->andThrows(Exception::class);
            $mock->shouldReceive('getOutput')->once()->andReturn('output');
            $mock->shouldReceive('getExitStatus')->never();
        });

        $this->mock(GetConsole::class, function(MockInterface $mock) use($server, $consoleMock) {
            $mock->shouldReceive('execute')->once()->withArgs([
                IsInstanceOf::anInstanceOf(SSHDetails::class),

            ])->andReturn($consoleMock);
        });

        $this->assertFalse(app(SudoExecutable::class)->getState($server)->getAvailable());
    }


    public function test_get_key()
    {
        $this->assertEquals(
            'sudo-executable',
            app(SudoExecutable::class)->getKey(),
        );
    }
}
