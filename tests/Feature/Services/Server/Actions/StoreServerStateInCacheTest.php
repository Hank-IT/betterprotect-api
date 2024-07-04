<?php

namespace Tests\Feature\Services\Server\Actions;

use App\Services\Server\Actions\StoreServerStateInCache;
use App\Services\Server\dtos\ServerState;
use App\Services\Server\dtos\ServerStateCheckResult;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class StoreServerStateInCacheTest extends TestCase
{
    public function test()
    {
        $hostname = fake()->domainName();

        $serverState = new ServerState(['postfix-database-available' => new ServerStateCheckResult(true, Carbon::now())]);

        app(StoreServerStateInCache::class)->execute($hostname, $serverState);

        $retrievedState = Cache::get("server-state-$hostname");

        $this->assertTrue($retrievedState->getPostfixDatabaseAvailable()->getAvailable());
    }
}
