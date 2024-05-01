<?php

namespace Tests\Feature\Services\Transport\Actions;

use App\Services\Transport\Actions\DeleteManyTransports;
use App\Services\Transport\Models\Transport;
use Tests\TestCase;

class DeleteManyTransportsTests extends TestCase
{
    public function test()
    {
        $deletableTransports = Transport::factory()->count(2)->create();
        $retainedTransport = Transport::factory()->create();

        $this->assertModelExists($deletableTransports[0]);
        $this->assertModelExists($deletableTransports[1]);
        $this->assertModelExists($retainedTransport);

        app(DeleteManyTransports::class)->execute([
            $deletableTransports[0]->getKey(),
            $deletableTransports[1]->getKey(),
        ]);

        $this->assertModelMissing($deletableTransports[0]);
        $this->assertModelMissing($deletableTransports[1]);
        $this->assertModelExists($retainedTransport);
    }
}
