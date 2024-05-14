<?php

namespace Services\BetterprotectPolicy\Repositories;

use App\Services\BetterprotectPolicy\Repositories\RelayRecipientRepository;
use App\Services\Recipients\Models\RelayRecipient;
use Tests\TestCase;

class RelayRecipientRepositoryTest extends TestCase
{
    public function test()
    {
        $activeRecipients = RelayRecipient::factory()->count(2)->create([
            'active' => true,
        ]);

        RelayRecipient::factory()->count(2)->create([
            'active' => false,
        ]);

        $result = app(RelayRecipientRepository::class)->get();

        $this->assertCount(2, $result);

        $this->assertEquals($activeRecipients[0]->getKey(), $result[0]->getKey());
        $this->assertEquals($activeRecipients[1]->getKey(), $result[1]->getKey());
    }
}
