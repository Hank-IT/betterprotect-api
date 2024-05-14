<?php

namespace Services\BetterprotectPolicy\Dtos;

use App\Services\BetterprotectPolicy\Actions\GetRelayDomains;
use App\Services\BetterprotectPolicy\Actions\GetTransportMaps;
use App\Services\BetterprotectPolicy\Dtos\RelayDomainPolicyDto;
use App\Services\BetterprotectPolicy\Dtos\TransportMapPolicyDto;
use Tests\TestCase;

class TransportMapPolicyDtoTest extends TestCase
{
    public function test()
    {
        $dto = new TransportMapPolicyDto;

        $this->assertInstanceOf(GetTransportMaps::class, $dto->getDataRetriever());
        $this->assertEquals('Transport entries are updating...', $dto->getDescription());
        $this->assertEquals('transport_maps', $dto->getTable());
    }
}
