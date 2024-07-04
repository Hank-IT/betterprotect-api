<?php

namespace Tests\Feature\Services\Rules\Actions;

use App\Services\Rules\Actions\GetClientSenderAccess;
use App\Services\Rules\Models\ClientSenderAccess;
use Tests\TestCase;

class GetClientSenderAccessTest extends TestCase
{
    public function test()
    {
        $clientSenderAccess = ClientSenderAccess::factory()->create();

        $action = app(GetClientSenderAccess::class);

        $result = $action->execute();

        $this->assertTrue(count($result) >= 1);
    }

    public function testSearch()
    {
        $clientSenderAccess = ClientSenderAccess::factory()->create();

        $action = app(GetClientSenderAccess::class);

        $result = $action->execute($clientSenderAccess->client_payload);

        $this->assertCount(1, $result);
    }
}
