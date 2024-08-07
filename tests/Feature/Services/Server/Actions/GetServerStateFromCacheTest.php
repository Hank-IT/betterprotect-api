<?php

namespace Tests\Feature\Services\Server\Actions;

use App\Services\Server\Actions\GetServerStateFromCache;
use App\Services\Server\dtos\ServerState;
use App\Services\Server\dtos\ServerStateCheckResult;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class GetServerStateFromCacheTest extends TestCase
{
    public function test()
    {
        $hostname = fake()->domainName();

        Cache::set("server-state-$hostname", new ServerState(['postfix-database-available' => new ServerStateCheckResult(true, Carbon::now())]));

        $serverState = app(GetServerStateFromCache::class)->execute($hostname);

        $this->assertTrue($serverState->getPostfixDatabaseAvailable()->getAvailable());
    }
}
