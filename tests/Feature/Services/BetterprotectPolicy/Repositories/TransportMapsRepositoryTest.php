<?php

namespace Services\BetterprotectPolicy\Repositories;

use App\Services\BetterprotectPolicy\Repositories\TransportMapRepository;
use App\Services\Transport\Models\Transport;
use Tests\TestCase;

class TransportMapsRepositoryTest extends TestCase
{
    public function test()
    {
        $activeTransports = Transport::factory()->count(2)->create([
            'active' => true,
        ]);

        Transport::factory()->count(2)->create([
            'active' => false,
        ]);

        $result = app(TransportMapRepository::class)->get();

        $this->assertCount(2, $result);

        $this->assertEquals($activeTransports[0]->getKey(), $result[0]->getKey());
        $this->assertEquals($activeTransports[1]->getKey(), $result[1]->getKey());
    }
}
