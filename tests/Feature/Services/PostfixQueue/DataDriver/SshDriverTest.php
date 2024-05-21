<?php

namespace Tests\Feature\Services\PostfixQueue\DataDriver;

use App\Services\PostfixQueue\DataDriver\SshDriver;
use Illuminate\Support\Facades\App;
use Mockery;
use Exception;
use App\Services\Server\Actions\GetConsole;
use App\Services\Server\dtos\SSHDetails;
use Hamcrest\Core\IsInstanceOf;
use HankIT\ConsoleAccess\ConsoleAccess;
use Mockery\MockInterface;
use Tests\TestCase;

class SshDriverTest extends TestCase
{
    public function test()
    {
        $sshDetails = SSHDetails::factory()->make();

        $this->expectException(Exception::class);

        $consoleMock = Mockery::mock(ConsoleAccess::class, function(MockInterface $mock) use($sshDetails) {
            $mock->shouldReceive('sudo')->once()->withArgs([$sshDetails->getSudoCommand()])->andReturn($mock);
            $mock->shouldReceive('bin')->once()->withArgs([$sshDetails->getPostqueueCommand()])->andReturn($mock);
            $mock->shouldReceive('param')->once()->withArgs(['-j'])->andReturn($mock);
            $mock->shouldReceive('exec')->once()->andReturn($mock);
            $mock->shouldReceive('getExitStatus')->once()->andReturn(1);
        });

        $this->mock(GetConsole::class, function(MockInterface $mock) use($consoleMock) {
            $mock->shouldReceive('execute')->once()->withArgs([
                IsInstanceOf::anInstanceOf(SSHDetails::class)
            ])->andReturn($consoleMock);
        });

        App::call([new SshDriver($sshDetails), 'get']);
    }
}
