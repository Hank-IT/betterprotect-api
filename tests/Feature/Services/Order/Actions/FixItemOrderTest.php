<?php

namespace Tests\Feature\Services\Order\Actions;

use App\Services\Order\Actions\FixItemOrder;
use App\Services\Rules\Models\ClientSenderAccess;
use Tests\TestCase;

class FixItemOrderTest extends TestCase
{
    public function test()
    {
        ClientSenderAccess::truncate();

        $zeroRule = ClientSenderAccess::factory()->create([
            'priority' => 0,
        ]);

        $thirdRule = ClientSenderAccess::factory()->create([
            'priority' => 3,
        ]);

        $fifthRule = ClientSenderAccess::factory()->create([
            'priority' => 5,
        ]);

        app(FixItemOrder::class)->execute(new ClientSenderAccess);

        $zeroRule->refresh();
        $thirdRule->refresh();
        $fifthRule->refresh();

        $this->assertEquals(0, $zeroRule->priority);
        $this->assertEquals(1, $thirdRule->priority);
        $this->assertEquals(2, $fifthRule->priority);
    }
}
