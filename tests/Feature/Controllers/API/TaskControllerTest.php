<?php

namespace Tests\Feature\Controllers\API;

use App\Services\Authentication\Models\User;
use App\Services\Tasks\Models\Task;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    public function test()
    {
        $user = User::factory()->create();

        $this->be($user);

        Task::factory()->count(5)->create();

        $response = $this->getJson(route('api.v1.tasks.index', [
            'page_number' => 1,
            'page_size' => 2,
        ]))->assertSuccessful();

        $this->assertCount(2, $response['data']);
    }
}
