<?php

namespace Tests\Feature\Services\Milter\Actions;

use App\Services\Milter\Actions\DeleteMilterException;
use App\Services\Milter\Models\MilterException;
use Tests\TestCase;

class DeleteMilterExceptionTest extends TestCase
{
    public function test()
    {
        $model = MilterException::factory()->create();

        $this->assertModelExists($model);

        app(DeleteMilterException::class)->execute($model);

        $this->assertModelMissing($model);
    }
}
