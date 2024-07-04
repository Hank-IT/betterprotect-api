<?php

namespace Tests\Feature\Services\Milter\Actions;

use App\Services\Milter\Actions\DeleteMilter;
use App\Services\Milter\Models\Milter;
use Tests\TestCase;

class DeleteMilterTest extends TestCase
{
    public function test()
    {
        $milter = Milter::factory()->create();

        $this->assertModelExists($milter);

        app(DeleteMilter::class)->execute($milter);

        $this->assertModelMissing($milter);
    }
}
