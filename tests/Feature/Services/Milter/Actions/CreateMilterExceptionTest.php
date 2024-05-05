<?php

namespace Tests\Feature\Services\Milter\Actions;

use App\Services\Milter\Actions\CreateMilterException;
use App\Services\Milter\Models\MilterException;
use Tests\TestCase;

class CreateMilterExceptionTest extends TestCase
{
    public function test()
    {
        $data = MilterException::factory()->make();

        $model = app(CreateMilterException::class)->execute(
            $data['client_type'],
            $data['client_payload'],
            $data['description'],
        );

        $this->assertModelExists($model);

        $this->assertDatabaseHas('milter_exceptions', [
            'client_type' => $data->client_type,
            'client_payload' => $data->client_payload,
            'description' => $data->description,
        ]);
    }
}
