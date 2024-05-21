<?php

namespace Tests\Feature\Services\PostfixQueue\Actions;

use App\Services\PostfixQueue\Actions\GetPostfixQueueEntriesFromCache;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class GetPostfixQueueEntriesFromCacheTest extends TestCase
{
    public function test()
    {
        $hostname = fake()->domainName;

        Cache::set(sprintf('postfix-queue-content-%s', $hostname), ['test']);

        $entries = app(GetPostfixQueueEntriesFromCache::class)->execute($hostname);

        $this->assertEquals('test', $entries[0]);
    }
}
