<?php

namespace Tests\Feature\Controllers\API;

use App\Services\Authentication\Models\User;
use App\Services\PostfixQueue\Actions\DeleteMailFromPostfixQueue;
use App\Services\PostfixQueue\Actions\FlushPostfixQueue;
use App\Services\PostfixQueue\Actions\GetPostfixQueueEntriesFromCache;
use App\Services\PostfixQueue\Dtos\PostfixQueueEntry;
use App\Services\PostfixQueue\Dtos\PostfixQueueEntryRecipient;
use App\Services\Server\dtos\SSHDetails;
use App\Services\Server\Models\Server;
use Carbon\Carbon;
use Hamcrest\Core\IsInstanceOf;
use Mockery\MockInterface;
use Tests\TestCase;

class PostfixQueueControllerTest extends TestCase
{
    public function test_index()
    {
        $user = User::factory()->create();

        $this->be($user);

        $server = Server::factory()->create();

        $entries = [
            new PostfixQueueEntry(
                'deferred',
                'A8E0E2256F',
                1715572939,
                24158,
                false,
                'MAILER-DAEMON',
                [
                    new PostfixQueueEntryRecipient(
                        'info@server11.rgranticsy.com',
                        'connect to server11.rgranticsy.com[103.177.125.93]:25: Connection refused'
                    )
                ]
            )
        ];

        $this->mock(GetPostfixQueueEntriesFromCache::class, function (MockInterface $mock) use ($server, $entries) {
            $mock->shouldReceive('execute')->once()->withArgs([
                $server->hostname,
            ])->andReturn($entries);
        });

        $response = $this->getJson(route('api.v1.server.postfix-queue.index', [
            'server' => $server->getKey(),
            'page_number' => 1,
            'page_size' => 2,
        ]))->assertSuccessful();

        $this->assertEquals($entries[0]->getQueueName(), $response['data'][0]['queue_name']);
        $this->assertEquals($entries[0]->getQueueId(), $response['data'][0]['queue_id']);
        $this->assertEquals($entries[0]->getArrivalTime(), Carbon::parse($response['data'][0]['arrival_time']));
        $this->assertEquals($entries[0]->getMessageSize(), $response['data'][0]['message_size']);
        $this->assertEquals($entries[0]->getForcedExpire(), $response['data'][0]['forced_expire']);
        $this->assertEquals($entries[0]->getSender(), $response['data'][0]['sender']);
        $this->assertEquals($entries[0]->getRecipients()[0]->getAddress(), $response['data'][0]['recipients'][0]['address']);
        $this->assertEquals($entries[0]->getRecipients()[0]->getDelayReason(), $response['data'][0]['recipients'][0]['delay_reason']);
    }

    public function test_store()
    {
        $user = User::factory()->create();

        $this->be($user);

        $server = Server::factory()->create();

        $this->mock(FlushPostfixQueue::class, function(MockInterface $mock) {
            $mock->shouldReceive('execute')->once()->withArgs([
                IsInstanceOf::anInstanceOf(SSHDetails::class),
            ]);
        });

        $this->postJson(route('api.v1.server.postfix-queue.flush', $server->getKey()))->assertSuccessful();
    }

    public function test_delete()
    {
        $user = User::factory()->create();

        $this->be($user);

        $server = Server::factory()->create();

        $queueId = fake()->word();

        $this->mock(DeleteMailFromPostfixQueue::class, function(MockInterface $mock) use($queueId) {
            $mock->shouldReceive('execute')->once()->withArgs([
                IsInstanceOf::anInstanceOf(SSHDetails::class),
                $queueId,
            ]);
        });

        $this->deleteJson(route('api.v1.server.postfix-queue.destroy', $server->getKey()), [
            'queue_id' => $queueId,
        ])->assertSuccessful();
    }
}
