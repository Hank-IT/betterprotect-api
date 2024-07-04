<?php

namespace Tests\Feature\Services\Tasks\Actions;

use App\Services\Tasks\Actions\TimeoutTasks;
use App\Services\Tasks\Enums\TaskStatusEnum;
use App\Services\Tasks\Models\Task;
use Carbon\Carbon;
use Tests\TestCase;

class TimeoutTasksTest extends TestCase
{
    public function test()
    {
        $timedoutTask = Task::factory()->create([
            'started_at' => Carbon::now()->subHours(3),
            'ended_at' => null,
        ]);

        $queuedTask = Task::factory()->create([
            'ended_at' => null,
        ]);

        app(TimeoutTasks::class)->execute();

        $timedoutTask->refresh();
        $queuedTask->refresh();

        $this->assertEquals(TaskStatusEnum::ERROR->value, $timedoutTask->status);
        $this->assertEquals(TaskStatusEnum::QUEUED->value, $queuedTask->status);
    }
}
