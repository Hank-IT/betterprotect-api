<?php

namespace Tests\Feature\Services\Tasks\Listeners;

use App\Services\Tasks\Enums\TaskStatusEnum;
use App\Services\Tasks\Events\TaskFailed;
use App\Services\Tasks\Events\TaskProgress;
use App\Services\Tasks\Listeners\TransitionTaskToFailed;
use App\Services\Tasks\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TransitionTaskToFailedTest extends TestCase
{
    public function test()
    {
        Event::fake();

        $task = Task::factory()->create([
            'ended_at' => null,
        ]);

        $event = new TaskFailed(
            $task->getKey(),
            'test',
            $endedAt = Carbon::today(),
        );

        $listener = new TransitionTaskToFailed;

        $listener->handle($event);

        $task->refresh();

        $this->assertEquals($endedAt, $task->ended_at);

        $this->assertEquals(TaskStatusEnum::ERROR->value, $task->status);

        Event::assertDispatched(TaskProgress::class);
    }
}
