<?php

namespace Tests\Feature\Services\Rules\Actions;

use App\Services\Rules\Actions\DeleteClientSenderAccess;
use App\Services\Rules\Models\ClientSenderAccess;
use Tests\TestCase;

class DeleteClientSenderAccessTest extends TestCase
{
    public function test()
    {
        $clientSenderAccess = ClientSenderAccess::factory()->create();

        $this->assertModelExists($clientSenderAccess);

        app(DeleteClientSenderAccess::class)->execute($clientSenderAccess);

        $this->assertModelMissing($clientSenderAccess);
    }
}
