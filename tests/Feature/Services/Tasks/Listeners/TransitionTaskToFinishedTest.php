<?php

namespace Services\Tasks\Listeners;

use App\Services\Tasks\Enums\TaskStatusEnum;
use App\Services\Tasks\Events\TaskFailed;
use App\Services\Tasks\Events\TaskFinished;
use App\Services\Tasks\Events\TaskProgress;
use App\Services\Tasks\Listeners\TransitionTaskToFailed;
use App\Services\Tasks\Listeners\TransitionTaskToFinished;
use App\Services\Tasks\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TransitionTaskToFinishedTest extends TestCase
{
    public function test()
    {
        $task = Task::factory()->create([
            'ended_at' => null,
        ]);

        $event = new TaskFinished(
            $task->getKey(),
            'test',
            $endedAt = Carbon::today(),
        );

        $listener = new TransitionTaskToFinished;

        $listener->handle($event);

        $task->refresh();

        $this->assertEquals($endedAt, $task->ended_at);

        $this->assertEquals(TaskStatusEnum::FINISHED->value, $task->status);
    }
}
