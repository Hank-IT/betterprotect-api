<?php

namespace Tests\Feature\Services\Recipients\Actions;

use App\Services\Recipients\Actions\FirstOrCreateRelayRecipient;
use App\Services\Recipients\Models\RelayRecipient;
use Tests\TestCase;

class CreateRelayRecipientTest extends TestCase
{
    public function test()
    {
        $recipient = RelayRecipient::factory()->make();

        $model = app(FirstOrCreateRelayRecipient::class)->execute($recipient->payload, $recipient->data_source);

        $this->assertModelExists($model);

        $this->assertEquals('OK', $model->action);
        $this->assertEquals($model->data_source, $recipient->data_source);
        $this->assertEquals($model->payload, $recipient->payload);
    }
}
