<?php

namespace Tests\Feature\Services\Milter\Actions;

use App\Services\Milter\Actions\CreateMilterException;
use App\Services\Milter\Models\Milter;
use App\Services\Milter\Models\MilterException;
use Tests\TestCase;

class CreateMilterExceptionTest extends TestCase
{
    public function test()
    {
        $milters = Milter::factory()->count(2)->create();

        $data = MilterException::factory()->make();

        $model = app(CreateMilterException::class)->execute(

        );
    }
}
