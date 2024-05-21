<?php

namespace Tests\Feature\Services\PostfixQueue\Actions;

use Carbon\Carbon;
use Mockery;
use App\Services\PostfixQueue\Actions\GetPostfixQueueEntriesFromServer;
use App\Services\Server\Actions\GetConsole;
use App\Services\Server\dtos\SSHDetails;
use Hamcrest\Core\IsInstanceOf;
use HankIT\ConsoleAccess\ConsoleAccess;
use Mockery\MockInterface;
use Tests\TestCase;

class GetPostfixQueueEntriesFromServerTest extends TestCase
{
    public function test()
    {
        $sshDetails = SSHDetails::factory()->make();

        $rows = file_get_contents(__DIR__ . '/../../../../../resources/postqueue_example.txt');

        $consoleMock = Mockery::mock(ConsoleAccess::class, function(MockInterface $mock) use($sshDetails, $rows) {
            $mock->shouldReceive('sudo')->once()->withArgs([$sshDetails->getSudoCommand()])->andReturn($mock);
            $mock->shouldReceive('bin')->once()->withArgs([$sshDetails->getPostqueueCommand()])->andReturn($mock);
            $mock->shouldReceive('param')->once()->withArgs(['-j'])->andReturn($mock);
            $mock->shouldReceive('exec')->once()->andReturn($mock);
            $mock->shouldReceive('getExitStatus')->once()->andReturn(0);
            $mock->shouldReceive('getOutput')->once()->andReturn($rows);
        });

        $this->mock(GetConsole::class, function(MockInterface $mock) use($consoleMock) {
            $mock->shouldReceive('execute')->once()->withArgs([
                IsInstanceOf::anInstanceOf(SSHDetails::class)
            ])->andReturn($consoleMock);
        });

        $result = app(GetPostfixQueueEntriesFromServer::class)->execute($sshDetails);

        $this->assertCount(3, $result);

        $this->assertEquals('deferred', $result[0]->getQueueName());
        $this->assertEquals('A8E0E2256F', $result[0]->getQueueId());
        $this->assertEquals(Carbon::parse(1715572939), $result[0]->getArrivalTime());
        $this->assertEquals(24158, $result[0]->getMessageSize());
        $this->assertFalse($result[0]->getForcedExpire());
        $this->assertEquals('MAILER-DAEMON', $result[0]->getSender());
        $this->assertCount(1, $result[0]->getRecipients());
        $this->assertEquals('info@server11.rgranticsy.com', $result[0]->getRecipients()[0]->getAddress());
        $this->assertEquals('connect to server11.rgranticsy.com[103.177.125.93]:25: Connection refused', $result[0]->getRecipients()[0]->getDelayReason());

        $this->assertEquals('deferred', $result[1]->getQueueName());
        $this->assertEquals('A11E922765', $result[1]->getQueueId());
        $this->assertEquals(Carbon::parse(1715562768), $result[1]->getArrivalTime());
        $this->assertEquals(13239, $result[1]->getMessageSize());
        $this->assertTrue($result[1]->getForcedExpire());
        $this->assertEquals('MAILER-DAEMON', $result[1]->getSender());
        $this->assertCount(2, $result[1]->getRecipients());
        $this->assertEquals('office@d3help.com', $result[1]->getRecipients()[0]->getAddress());
        $this->assertEquals('connect to d3help.com[66.179.248.82]:25: Connection timed out', $result[1]->getRecipients()[0]->getDelayReason());
        $this->assertEquals('mail@contoso.com', $result[1]->getRecipients()[1]->getAddress());
        $this->assertEquals('connect to mx00.contoso.com[66.179.248.82]:25: Connection timed out', $result[1]->getRecipients()[1]->getDelayReason());
    }
}
