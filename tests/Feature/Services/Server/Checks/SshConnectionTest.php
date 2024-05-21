<?php

namespace Tests\Feature\Services\Server\Checks;

use Exception;
use HankIT\ConsoleAccess\Adapters\SshAdapter\Adapter;
use HankIT\ConsoleAccess\ConsoleAccess;
use HankIT\ConsoleAccess\Exceptions\ConnectionNotPossibleException;
use HankIT\ConsoleAccess\Exceptions\PublicKeyMismatchException;
use Illuminate\Support\Str;
use Mockery;
use App\Services\Server\Actions\GetConsole;
use App\Services\Server\Checks\SshConnection;
use App\Services\Server\Models\Server;
use Mockery\MockInterface;
use Tests\TestCase;

class SshConnectionTest extends TestCase
{
    public function test_successful()
    {
        $server = Server::factory()->create();

        $consoleMock = Mockery::mock(ConsoleAccess::class, function(MockInterface $mock) {
            $mock->shouldReceive('bin')->withArgs(['test'])->once()->andReturn($mock);
            $mock->shouldReceive('param')->withArgs(['-e'])->once()->andReturn($mock);
            $mock->shouldReceive('param')->withArgs(['/dev/null'])->once()->andReturn($mock);
            $mock->shouldReceive('exec')->once()->andReturn($mock);
        });

        $this->mock(GetConsole::class, function(MockInterface $mock) use($consoleMock) {
            $mock->shouldReceive('execute')->once()->andReturn($consoleMock);
        });

        $this->assertTrue(app(SshConnection::class)->getState($server)->getAvailable());
    }

    public function test_connection_not_possible()
    {
        $server = Server::factory()->create();

        $consoleMock = Mockery::mock(ConsoleAccess::class, function(MockInterface $mock) {
            $mock->shouldReceive('bin')->withArgs(['test'])->once()->andReturn($mock);
            $mock->shouldReceive('param')->withArgs(['-e'])->once()->andReturn($mock);
            $mock->shouldReceive('param')->withArgs(['/dev/null'])->once()->andReturn($mock);
            $mock->shouldReceive('getOutput')->once()->andReturn($mock);
            $mock->shouldReceive('exec')->once()->andThrow(ConnectionNotPossibleException::class);
        });

        $this->mock(GetConsole::class, function(MockInterface $mock) use($consoleMock) {
            $mock->shouldReceive('execute')->once()->andReturn($consoleMock);
        });

        $state = app(SshConnection::class)->getState($server);

        $this->assertFalse($state->getAvailable());
        $this->assertStringContainsString('Connection not possible', $state->getDescription());
    }

    public function test_public_key_mismatch()
    {
        $server = Server::factory()->create();

        $key = (string) Str::uuid();

        $adapterMock = Mockery::mock(Adapter::class, function(MockInterface $mock) use($key) {
            $mock->shouldReceive('getServerPublicHostKey')->once()->andReturn($key);
        });

        $consoleMock = Mockery::mock(ConsoleAccess::class, function(MockInterface $mock) use($adapterMock) {
            $mock->shouldReceive('bin')->withArgs(['test'])->once()->andReturn($mock);
            $mock->shouldReceive('param')->withArgs(['-e'])->once()->andReturn($mock);
            $mock->shouldReceive('param')->withArgs(['/dev/null'])->once()->andReturn($mock);
            $mock->shouldReceive('getOutput')->once()->andReturn($mock);
            $mock->shouldReceive('getAdapter')->once()->andReturn($adapterMock);
            $mock->shouldReceive('exec')->once()->andThrow(PublicKeyMismatchException::class);
        });

        $this->mock(GetConsole::class, function(MockInterface $mock) use($consoleMock) {
            $mock->shouldReceive('execute')->once()->andReturn($consoleMock);
        });

        $state = app(SshConnection::class)->getState($server);

        $this->assertFalse($state->getAvailable());
        $this->assertStringContainsString('Public key mismatch', $state->getDescription());
        $this->assertStringContainsString($key, $state->getDescription());
    }

    public function test_generic_exception()
    {
        $server = Server::factory()->create();

        $consoleMock = Mockery::mock(ConsoleAccess::class, function(MockInterface $mock) {
            $mock->shouldReceive('bin')->withArgs(['test'])->once()->andReturn($mock);
            $mock->shouldReceive('param')->withArgs(['-e'])->once()->andReturn($mock);
            $mock->shouldReceive('param')->withArgs(['/dev/null'])->once()->andReturn($mock);
            $mock->shouldReceive('getOutput')->once()->andReturn($mock);
            $mock->shouldReceive('exec')->once()->andThrow(new Exception('custom-message'));
        });

        $this->mock(GetConsole::class, function(MockInterface $mock) use($consoleMock) {
            $mock->shouldReceive('execute')->once()->andReturn($consoleMock);
        });

        $state = app(SshConnection::class)->getState($server);

        $this->assertFalse($state->getAvailable());
        $this->assertStringContainsString('custom-message', $state->getDescription());
    }

    public function test_get_key()
    {
        $this->assertEquals(
            'ssh-connection',
            app(SshConnection::class)->getKey(),
        );
    }
}
