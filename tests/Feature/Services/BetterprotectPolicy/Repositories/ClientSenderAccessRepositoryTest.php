<?php

namespace Tests\Feature\Services\BetterprotectPolicy\Repositories;

use App\Services\BetterprotectPolicy\Repositories\ClientSenderAccessRepository;
use App\Services\Rules\Models\ClientSenderAccess;
use Tests\TestCase;

class ClientSenderAccessRepositoryTest extends TestCase
{
    public function test()
    {
        $activeSender = ClientSenderAccess::factory()->count(2)->create([
            'active' => true,
        ]);

        ClientSenderAccess::factory()->count(2)->create([
            'active' => false,
        ]);

        $result = app(ClientSenderAccessRepository::class)->get();

        $this->assertCount(2, $result);

        $this->assertEquals($activeSender[0]->getKey(), $result[0]->getKey());
        $this->assertEquals($activeSender[1]->getKey(), $result[1]->getKey());
    }
}
