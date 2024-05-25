<?php

namespace Tests\Feature\Services\Order\Actions;

use App\Services\Order\Actions\FixItemOrder;
use App\Services\Rules\Models\ClientSenderAccess;
use Tests\TestCase;

class FixItemOrderTest extends TestCase
{
    public function test()
    {
        $zeroRule = ClientSenderAccess::factory()->create([
            'priority' => 0,
        ]);

        $firstRule = ClientSenderAccess::factory()->create([
            'priority' => 1,
        ]);

        $fifthRule = ClientSenderAccess::factory()->create([
            'priority' => 5,
        ]);

        app(FixItemOrder::class)->execute(new ClientSenderAccess);

        $zeroRule->refresh();

        $this->assertEquals(1, $firstRule->priority);
        $this->assertEquals(2, $zeroRule->priority);
        $this->assertEquals(5, $fifthRule->priority);
    }
}
