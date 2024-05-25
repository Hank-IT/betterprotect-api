<?php

namespace Tests\Feature\Services\Order\Actions;

use App\Services\Order\Actions\MoveItemDown;
use App\Services\Rules\Models\ClientSenderAccess;
use Tests\TestCase;

class MoveItemDownTest extends TestCase
{
    public function test()
    {
        $rule = ClientSenderAccess::factory()->create([
            'priority' => 2,
        ]);

        app(MoveItemDown::class)->execute($rule);

        $this->assertEquals(3, $rule->priority);
    }
}
