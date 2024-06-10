<?php

namespace Tests\Feature\Services\Order\Actions;

use App\Services\Order\Actions\MoveItemDown;
use App\Services\Order\Actions\MoveItemUp;
use App\Services\Rules\Models\ClientSenderAccess;
use Tests\TestCase;

class MoveItemUpTest extends TestCase
{
    public function test()
    {
        ClientSenderAccess::truncate();

        $otherRule = ClientSenderAccess::factory()->create([
            'priority' => 2,
        ]);

        $rule = ClientSenderAccess::factory()->create([
            'priority' => 3,
        ]);

        app(MoveItemUp::class)->execute($rule);

        $otherRule->refresh();
        $rule->refresh();

        $this->assertEquals(0, $rule->priority);
        $this->assertEquals(1, $otherRule->priority);
    }
}
