<?php

namespace Services\BetterprotectPolicy\Dtos;

use App\Services\BetterprotectPolicy\Actions\GetRelayDomains;
use App\Services\BetterprotectPolicy\Dtos\RelayDomainPolicyDto;
use Tests\TestCase;

class RelayDomainPolicyDtoTest extends TestCase
{
    public function test()
    {
        $dto = new RelayDomainPolicyDto;

        $this->assertInstanceOf(GetRelayDomains::class, $dto->getDataRetriever());
        $this->assertEquals('Relay domains are updating...', $dto->getDescription());
        $this->assertEquals('relay_domains', $dto->getTable());
    }
}
