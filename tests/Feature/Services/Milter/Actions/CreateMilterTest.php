<?php

namespace Tests\Feature\Services\Milter\Actions;
use App\Services\Milter\Actions\CreateMilter;
use App\Services\Milter\Models\Milter;
use Tests\TestCase;

class CreateMilterTest extends TestCase
{
    public function test()
    {
        $data = Milter::factory()->make();

        $milter = app(CreateMilter::class)->execute(
            $data->name,
            $data->definition,
            $data->description,
        );

        $this->assertModelExists($milter);

        $this->assertDatabaseHas('milters', [
            'name' => $data->name,
            'definition' => $data->definition,
            'description' => $data->description,
        ]);
    }
}
