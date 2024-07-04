<?php

namespace Tests\Feature\Services\Server\Actions;

use App\Services\Server\Actions\DeleteServer;
use App\Services\Server\Models\Server;
use Tests\TestCase;

class DeleteServerTest extends TestCase
{
    public function test()
    {
        $server = Server::factory()->create();

        $this->assertModelExists($server);

        app(DeleteServer::class)->execute($server);

        $this->assertModelMissing($server);
    }
}
