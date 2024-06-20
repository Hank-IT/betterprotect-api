<?php

namespace Tests\Feature\Controllers\API\Policy;

use App\Services\Authentication\Models\User;
use App\Services\Milter\Models\Milter;
use App\Services\Milter\Models\MilterException;
use Tests\TestCase;

class MilterControllerTest extends TestCase
{
    public function testIndex()
    {
        MilterException::truncate();
        Milter::truncate();

        $user = User::factory()->create();

        $this->be($user);

        $milters = Milter::factory()->count(2)->create();

        $this->getJson(route('api.v1.milter.index'))
            ->assertSuccessful()
            ->assertJsonPath('data.0.id', $milters[0]->getKey());
    }

    public function testStore()
    {
        $user = User::factory()->create();

        $this->be($user);

        $data = Milter::factory()->make();

        $this->postJson(route('api.v1.milter.store'), [
            'name' => $data->name,
            'definition' => $data->definition,
            'description' => $data->description,
        ])
            ->assertSuccessful();

        $this->assertDatabaseHas('milters', [
            'name' => $data->name,
            'definition' => $data->definition,
            'description' => $data->description,
        ]);
    }

    public function testDestroy()
    {
        $user = User::factory()->create();

        $this->be($user);

        $milter = Milter::factory()->create();

        $this->deleteJson(route('api.v1.milter.destroy', $milter->getKey()))->assertSuccessful();

        $this->assertModelMissing($milter);
    }
}
