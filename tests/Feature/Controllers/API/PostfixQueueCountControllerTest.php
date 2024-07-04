<?php

namespace Tests\Feature\Controllers\API;

use App\Services\Authentication\Models\User;
use App\Services\PostfixQueue\Actions\GetPostfixQueueEntriesFromCache;
use App\Services\Server\Models\Server;
use Mockery\MockInterface;
use Tests\TestCase;

class PostfixQueueCountControllerTest extends TestCase
{
    public function test()
    {
        $user = User::factory()->create();

        $this->be($user);

        $server = Server::factory()->create();

        $entries = [
            'test-1',
            'test-2',
            'test-3',
        ];

        $this->mock(GetPostfixQueueEntriesFromCache::class, function (MockInterface $mock) use ($server, $entries) {
            $mock->shouldReceive('execute')->once()->withArgs([
                $server->hostname,
            ])->andReturn($entries);
        });

        $response = $this->getJson(route('api.v1.server.postfix-queue.count', [
            'server' => $server->getKey(),
        ]))->assertSuccessful();

        $this->assertEquals(3, $response['data']['count']);
    }
}
