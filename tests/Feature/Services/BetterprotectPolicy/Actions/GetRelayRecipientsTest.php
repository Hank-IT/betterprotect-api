<?php

namespace Tests\Feature\Services\BetterprotectPolicy\Actions;

use App\Services\BetterprotectPolicy\Actions\GetRelayRecipients;
use App\Services\BetterprotectPolicy\Repositories\RelayRecipientRepository;
use App\Services\Recipients\Models\RelayRecipient;
use Mockery\MockInterface;
use Tests\TestCase;

class GetRelayRecipientsTest extends TestCase
{
    public function test()
    {
        $relayRecipients = RelayRecipient::factory()->count(2)->create();

        $this->mock(RelayRecipientRepository::class, function(MockInterface $mock) use($relayRecipients) {
            $mock->shouldReceive('get')->once()->andReturn($relayRecipients);
        });

        $data = app(GetRelayRecipients::class)->execute();

        $this->assertCount(2, $data);

        $this->assertEquals($relayRecipients[0]->payload, $data[0]['payload']);
        $this->assertEquals($relayRecipients[0]->action, $data[0]['action']);

        $this->assertEquals($relayRecipients[1]->payload, $data[1]['payload']);
        $this->assertEquals($relayRecipients[1]->action, $data[1]['action']);
    }
}
