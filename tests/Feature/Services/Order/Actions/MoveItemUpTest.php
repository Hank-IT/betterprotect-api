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
        $rule = ClientSenderAccess::factory()->create([
            'priority' => 3,
        ]);

        app(MoveItemUp::class)->execute($rule);

        $this->assertEquals(1, $rule->priority);
    }
}
