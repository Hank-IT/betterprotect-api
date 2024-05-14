<?php

namespace Services\BetterprotectPolicy\Repositories;

use App\Services\BetterprotectPolicy\Repositories\ClientSenderAccessRepository;
use App\Services\BetterprotectPolicy\Repositories\MilterExceptionRepository;
use App\Services\BetterprotectPolicy\Repositories\RelayDomainRepository;
use App\Services\Milter\Models\MilterException;
use App\Services\RelayDomains\Models\RelayDomain;
use App\Services\Rules\Models\ClientSenderAccess;
use Tests\TestCase;

class RelayDomainRepositoryTest extends TestCase
{
    public function test()
    {
        $activeDomains = RelayDomain::factory()->count(2)->create([
            'active' => true,
        ]);

        RelayDomain::factory()->count(2)->create([
            'active' => false,
        ]);

        $result = app(RelayDomainRepository::class)->get();

        $this->assertCount(2, $result);

        $this->assertEquals($activeDomains[0]->domain, $result[0]->domain);
        $this->assertEquals($activeDomains[1]->domain, $result[1]->domain);
    }
}
