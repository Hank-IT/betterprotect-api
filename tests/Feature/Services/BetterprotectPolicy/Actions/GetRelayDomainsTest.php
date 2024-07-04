<?php

namespace Tests\Feature\Services\BetterprotectPolicy\Actions;

use App\Services\BetterprotectPolicy\Actions\GetRelayDomains;
use App\Services\BetterprotectPolicy\Repositories\RelayDomainRepository;
use App\Services\RelayDomains\Models\RelayDomain;
use Illuminate\Database\Eloquent\Collection;
use Mockery\MockInterface;
use Tests\TestCase;

class GetRelayDomainsTest extends TestCase
{
    public function test()
    {
        $relayDomains = RelayDomain::factory()->count(2)->create();

        $this->mock(RelayDomainRepository::class, function(MockInterface $mock) use($relayDomains) {
            $mock->shouldReceive('get')->once()->andReturn(new Collection($relayDomains));
        });

        $data = app(GetRelayDomains::class)->execute();

        $this->assertCount(2, $data);

        $this->assertEquals($relayDomains[0]->getKey(), $data[0]['id']);
        $this->assertEquals($relayDomains[1]->getKey(), $data[1]['id']);
    }
}
