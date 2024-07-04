<?php

namespace Tests\Feature\Services\PostfixQueue\Actions;

use App\Services\PostfixQueue\Actions\GetPostfixQueueCacheKeyForServer;
use Tests\TestCase;

class GetPostfixQueueCacheKeyForServerTest extends TestCase
{
    public function test()
    {
        $hostname = fake()->domainName();

        $this->assertEquals(
            "postfix-queue-content-$hostname", app(GetPostfixQueueCacheKeyForServer::class)->execute($hostname),
        );
    }
}
