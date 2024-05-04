<?php

namespace Tests\Feature\Services\Rules\Actions;

use App\Services\Order\Actions\OrderItems;
use App\Services\Rules\Actions\CreateClientSenderAccess;
use App\Services\Rules\Models\ClientSenderAccess;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class CreateClientSenderAccessTest extends TestCase
{
    public function test()
    {
        $props = ClientSenderAccess::factory()->make();

        $this->mock(OrderItems::class, function(MockInterface $mock) {
            $mock->shouldReceive('reOrder')->once()->withArgs([
                Mockery::on(function($model) {
                    return $model instanceof ClientSenderAccess;
                })
            ]);
        });

        $action = app(CreateClientSenderAccess::class);

        $model = $action->execute(
            $props->client_type,
            $props->client_payload,
            $props->sender_type,
            $props->sender_payload,
            $props->action,
            $props->message,
            $props->description,
        );

        $this->assertModelExists($model);

        $this->assertDatabaseHas('client_sender_access', [
            'client_type' => $props->client_type,
            'client_payload' => $props->client_payload,
            'sender_type' => $props->sender_type,
            'sender_payload' => $props->sender_payload,
            'action' => $props->action,
            'message' => $props->message,
            'description' => $props->description,
        ]);
    }
}
