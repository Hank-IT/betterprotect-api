<?php

namespace Tests\Feature\Services\BetterprotectPolicy\Dtos;

use App\Services\BetterprotectPolicy\Actions\GetClientSenderAccess;
use App\Services\BetterprotectPolicy\Dtos\ClientSenderAccessPolicyDto;
use Tests\TestCase;

class ClientSenderAccessPolicyDtoTest extends TestCase
{
    public function test()
    {
        $dto = new ClientSenderAccessPolicyDto;

        $this->assertInstanceOf(GetClientSenderAccess::class, $dto->getDataRetriever());
        $this->assertEquals('Rules are updating...', $dto->getDescription());
        $this->assertEquals('client_sender_access', $dto->getTable());
    }
}
