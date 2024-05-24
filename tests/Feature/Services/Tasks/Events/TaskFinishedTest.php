<?php

namespace Services\Tasks\Events;

use App\Services\Tasks\Events\TaskCreated;
use App\Services\Tasks\Events\TaskFailed;
use App\Services\Tasks\Events\TaskFinished;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class TaskFinishedTest extends TestCase
{
    public function test()
    {
        $event = new TaskFinished(
            (string) Str::uuid(),
            'test',
            Carbon::now(),
        );

        $this->assertEquals('private-task', $event->broadcastOn()->name);
        $this->assertEquals('private-task', (string) $event->broadcastOn());
    }
}
