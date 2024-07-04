<?php

namespace Tests\Feature\Services\Recipients\Actions;

use App\Services\Recipients\Actions\DeleteRelayRecipient;
use App\Services\Recipients\Models\RelayRecipient;
use Tests\TestCase;

class DeleteRelayRecipientTest extends TestCase
{
    public function test()
    {
        $recipient = RelayRecipient::factory()->create();

        $this->assertModelExists($recipient);

        app(DeleteRelayRecipient::class)->execute($recipient);

        $this->assertModelMissing($recipient);
    }
}
