<?php

namespace Tests\Feature\Services\Tasks\Commands;

use App\Services\Tasks\Actions\TimeoutTasks;
use Mockery\MockInterface;
use Tests\TestCase;

class TaskCleanCommandTest extends TestCase
{
    public function test()
    {
        $this->mock(TimeoutTasks::class, function(MockInterface $mock) {
            $mock->shouldReceive('execute')->once();
        });

        $this->artisan('task:clean')->assertSuccessful();
    }
}
