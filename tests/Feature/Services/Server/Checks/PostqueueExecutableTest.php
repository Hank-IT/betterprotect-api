<?php

namespace Tests\Feature\Services\Server\Checks;

use App\Services\Server\Checks\PostqueueExecutable;
use App\Services\Server\dtos\SSHDetails;
use Hamcrest\Core\IsInstanceOf;
use HankIT\ConsoleAccess\ConsoleAccess;
use Mockery;
use App\Services\Server\Actions\GetConsole;
use App\Services\Server\Models\Server;
use Mockery\MockInterface;
use Tests\TestCase;

class PostqueueExecutableTest extends TestCase
{
    public function test_executable()
    {
        $server = Server::factory()->create();

        $sshDetails = $server->getSSHDetails();

        $consoleMock = Mockery::mock(ConsoleAccess::class, function (MockInterface $mock) use ($sshDetails) {
            $mock->shouldReceive('sudo')->once()->withArgs([$sshDetails->getSudoCommand() . ' -n'])->andReturn($mock);
            $mock->shouldReceive('bin')->once()->withArgs([$sshDetails->getPostqueueCommand()])->andReturn($mock);
            $mock->shouldReceive('param')->once()->withArgs(['-j'])->andReturn($mock);
            $mock->shouldReceive('exec')->once()->andReturn($mock);
            $mock->shouldReceive('getOutput')->once()->andReturn('output');
            $mock->shouldReceive('getExitStatus')->once()->andReturn(0);
        });

        $this->mock(GetConsole::class, function(MockInterface $mock) use($consoleMock) {
            $mock->shouldReceive('execute')->once()->withArgs([
                IsInstanceOf::anInstanceOf(SSHDetails::class),
            ])->andReturn($consoleMock);
        });

        $this->assertTrue(app(PostqueueExecutable::class)->getState($server));
    }

    public function test_error()
    {
        $server = Server::factory()->create();

        $sshDetails = $server->getSSHDetails();

        $consoleMock = Mockery::mock(ConsoleAccess::class, function (MockInterface $mock) use ($sshDetails) {
            $mock->shouldReceive('sudo')->once()->withArgs([$sshDetails->getSudoCommand() . ' -n'])->andReturn($mock);
            $mock->shouldReceive('bin')->once()->withArgs([$sshDetails->getPostqueueCommand()])->andReturn($mock);
            $mock->shouldReceive('param')->once()->withArgs(['-j'])->andReturn($mock);
            $mock->shouldReceive('exec')->once()->andReturn($mock);
            $mock->shouldReceive('getOutput')->once()->andReturn('output');
            $mock->shouldReceive('getExitStatus')->once()->andReturn(1);
        });

        $this->mock(GetConsole::class, function(MockInterface $mock) use($server, $consoleMock) {
            $mock->shouldReceive('execute')->once()->withArgs([
                IsInstanceOf::anInstanceOf(SSHDetails::class),

            ])->andReturn($consoleMock);
        });

        $this->assertFalse(app(PostqueueExecutable::class)->getState($server));
    }

    public function test_get_key()
    {
        $this->assertEquals(
            'postqueue-executable',
            app(PostqueueExecutable::class)->getKey(),
        );
    }
}
