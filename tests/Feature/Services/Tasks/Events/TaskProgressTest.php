<?php

namespace Services\Tasks\Events;

use App\Services\Tasks\Events\TaskCreated;
use App\Services\Tasks\Events\TaskFailed;
use App\Services\Tasks\Events\TaskProgress;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class TaskProgressTest extends TestCase
{
    public function test()
    {
        $event = new TaskProgress(
            (string) Str::uuid(),
            'test',
        );

        $this->assertEquals('private-task', $event->broadcastOn()->name);
        $this->assertEquals('private-task', (string) $event->broadcastOn());
    }
}
