<?php

namespace Tests\Feature\Services\Tasks\Events;

use App\Services\Tasks\Events\TaskCreated;
use Illuminate\Support\Str;
use Tests\TestCase;

class TaskCreatedTest extends TestCase
{
    public function test()
    {
        $event = new TaskCreated(
            (string) Str::uuid(),
            'test',
            fake()->userName,
        );

        $this->assertEquals('private-task', $event->broadcastOn()->name);
        $this->assertEquals('private-task', (string) $event->broadcastOn());
    }
}
