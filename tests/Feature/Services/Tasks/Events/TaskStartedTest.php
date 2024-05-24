<?php

namespace Services\Tasks\Events;

use App\Services\Tasks\Events\TaskCreated;
use App\Services\Tasks\Events\TaskFailed;
use App\Services\Tasks\Events\TaskStarted;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class
TaskStartedTest extends TestCase
{
    public function test()
    {
        $event = new TaskStarted(
            (string) Str::uuid(),
            Carbon::now(),
        );

        $this->assertEquals('private-task', $event->broadcastOn()->name);
        $this->assertEquals('private-task', (string) $event->broadcastOn());
    }
}
