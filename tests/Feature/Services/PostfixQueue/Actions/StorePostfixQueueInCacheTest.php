<?php

namespace Tests\Feature\Services\PostfixQueue\Actions;

use App\Services\PostfixQueue\Actions\StorePostfixQueueInCache;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class StorePostfixQueueInCacheTest extends TestCase
{
    public function test()
    {
        $hostname = fake()->domainName;

        $entries = ['test'];

        app(StorePostfixQueueInCache::class)->execute($hostname, $entries);

        $this->assertEquals($entries, Cache::get(sprintf('postfix-queue-content-%s', $hostname)));
    }
}
