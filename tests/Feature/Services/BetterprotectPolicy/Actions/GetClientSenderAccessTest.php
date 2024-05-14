<?php

namespace Tests\Feature\Services\BetterprotectPolicy\Actions;

use App\Services\BetterprotectPolicy\Enums\PolicyDecisions;
use App\Services\BetterprotectPolicy\Repositories\ClientSenderAccessRepository;
use App\Services\BetterprotectPolicy\Actions\GetClientSenderAccess;
use App\Services\Rules\Models\ClientSenderAccess;
use Mockery\MockInterface;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

class GetClientSenderAccessTest extends TestCase
{
    public function test()
    {
        $rejectSender = ClientSenderAccess::factory()->create([
            'action' => 'reject',
            'message' => null,
        ]);

        $okSender = ClientSenderAccess::factory()->create([
            'action' => 'ok',
            'message' => null,
        ]);

        $rejectSenderWithCustomMessage = ClientSenderAccess::factory()->create([
            'action' => 'reject',
            'message' => fake()->word,
        ]);

        $this->mock(ClientSenderAccessRepository::class, function(MockInterface $mock) use($rejectSender, $okSender, $rejectSenderWithCustomMessage) {
            $mock->shouldReceive('get')->once()->andReturn(new Collection([
                $rejectSender, $okSender, $rejectSenderWithCustomMessage
            ]));
        });

        $result = app(GetClientSenderAccess::class)->execute();

        $this->assertCount(3, $result);

        $this->assertEquals($rejectSender->client_type, $result[0]['client_type']);
        $this->assertEquals($rejectSender->client_payload, $result[0]['client_payload']);
        $this->assertEquals($rejectSender->sender_type, $result[0]['sender_type']);
        $this->assertEquals($rejectSender->sender_payload, $result[0]['sender_payload']);
        $this->assertEquals(sprintf('%s %s', $rejectSender->action, PolicyDecisions::POLICY_DENIED->value), $result[0]['action']);
        $this->assertEquals($rejectSender->priority, $result[0]['priority']);
        $this->assertCount(6, $result[0]);

        $this->assertEquals($okSender->client_type, $result[1]['client_type']);
        $this->assertEquals($okSender->client_payload, $result[1]['client_payload']);
        $this->assertEquals($okSender->sender_type, $result[1]['sender_type']);
        $this->assertEquals($okSender->sender_payload, $result[1]['sender_payload']);
        $this->assertEquals(sprintf('%s %s', $okSender->action, PolicyDecisions::POLICY_GRANTED->value), $result[1]['action']);
        $this->assertEquals($okSender->priority, $result[1]['priority']);
        $this->assertCount(6, $result[1]);

        $this->assertEquals($rejectSenderWithCustomMessage->client_type, $result[2]['client_type']);
        $this->assertEquals($rejectSenderWithCustomMessage->client_payload, $result[2]['client_payload']);
        $this->assertEquals($rejectSenderWithCustomMessage->sender_type, $result[2]['sender_type']);
        $this->assertEquals($rejectSenderWithCustomMessage->sender_payload, $result[2]['sender_payload']);
        $this->assertEquals(sprintf('%s %s - %s', $rejectSenderWithCustomMessage->action, PolicyDecisions::POLICY_DENIED->value, $rejectSenderWithCustomMessage->message), $result[2]['action']);
        $this->assertEquals($rejectSenderWithCustomMessage->priority, $result[2]['priority']);
        $this->assertCount(6, $result[2]);
    }
}
