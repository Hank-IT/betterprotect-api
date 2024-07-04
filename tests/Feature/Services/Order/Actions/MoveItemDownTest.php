<?php

namespace Tests\Feature\Services\Order\Actions;

use App\Services\Order\Actions\MoveItemDown;
use App\Services\Rules\Models\ClientSenderAccess;
use Tests\TestCase;

class MoveItemDownTest extends TestCase
{
    public function test()
    {
        ClientSenderAccess::truncate();

        $rule = ClientSenderAccess::factory()->create([
            'priority' => 1,
        ]);

        $otherRule = ClientSenderAccess::factory()->create([
            'priority' => 2,
        ]);

        app(MoveItemDown::class)->execute($rule);

        $otherRule->refresh();
        $rule->refresh();

        $this->assertEquals(0, $otherRule->priority);
        $this->assertEquals(1, $rule->priority);
    }
}
