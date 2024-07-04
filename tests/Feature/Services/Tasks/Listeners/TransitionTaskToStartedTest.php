<?php

namespace Services\Tasks\Listeners;

use App\Services\Tasks\Enums\TaskStatusEnum;
use App\Services\Tasks\Events\TaskFailed;
use App\Services\Tasks\Events\TaskFinished;
use App\Services\Tasks\Events\TaskProgress;
use App\Services\Tasks\Events\TaskStarted;
use App\Services\Tasks\Listeners\TransitionTaskToFailed;
use App\Services\Tasks\Listeners\TransitionTaskToFinished;
use App\Services\Tasks\Listeners\TransitionTaskToStarted;
use App\Services\Tasks\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TransitionTaskToStartedTest extends TestCase
{
    public function test()
    {
        $task = Task::factory()->create([
            'ended_at' => null,
        ]);

        $event = new TaskStarted(
            $task->getKey(),
            $startedAt = Carbon::today(),
        );

        $listener = new TransitionTaskToStarted;

        $listener->handle($event);

        $task->refresh();

        $this->assertEquals($startedAt, $task->started_at);

        $this->assertEquals(TaskStatusEnum::RUNNING->value, $task->status);
    }
}
