<?php

namespace Tests\Feature\Controllers\API;

use App\Services\Tasks\Models\TaskProgress;
use App\Services\Authentication\Models\User;
use App\Services\Tasks\Models\Task;
use Illuminate\Support\Str;
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

    public function test_task_progress()
    {
        $user = User::factory()->create();

        $this->be($user);

        $task = Task::factory()->create();

        $progress = TaskProgress::create([
            'task_id' => $task->getKey(),
            'description' => $description = (string) Str::uuid(),
        ]);

        $response = $this->getJson(route('api.v1.tasks.index', [
            'page_number' => 1,
            'page_size' => 1,
        ]))->assertSuccessful();

        $this->assertCount(1, $response['data']);
        $this->assertEquals($progress->getKey(), $response['data'][0]['progress'][0]['id']);
        $this->assertEquals($description, $response['data'][0]['progress'][0]['description']);
    }
}
