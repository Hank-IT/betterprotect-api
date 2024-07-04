<?php

namespace Tests\Feature\Services\Server\Jobs;

use App\Services\Server\Database;
use App\Services\Server\dtos\DatabaseDetails;
use Hamcrest\Core\IsInstanceOf;
use Mockery;
use App\Services\Server\Factories\DatabaseFactory;
use App\Services\Server\Jobs\MigrateServerDatabase;
use App\Services\Server\Models\Server;
use App\Services\Tasks\Events\TaskCreated;
use App\Services\Tasks\Events\TaskFailed;
use App\Services\Tasks\Events\TaskFinished;
use App\Services\Tasks\Events\TaskProgress;
use App\Services\Tasks\Events\TaskStarted;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Mockery\MockInterface;
use Tests\TestCase;

class MigrateServerDatabaseTest extends TestCase
{
    public static function dataProvider() {
        return [
            [
                'postfix',
            ],
            [
                'log',
            ]
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function test_success(string $database)
    {
        Event::fake();

        $server = Server::factory()->create();
        $username = fake()->userName;
        $uniqueTaskId = (string) Str::uuid();

        $databaseMock = Mockery::mock(Database::class, function(MockInterface $mock) {
            $mock->shouldReceive('migrate')->once()->andReturn(0);
        });

        $databaseFactoryMock = Mockery::mock(DatabaseFactory::class, function(MockInterface $mock) use($databaseMock, $database) {
            $mock->shouldReceive('make')->once()->withArgs([
                $database,
                IsInstanceOf::anInstanceOf(DatabaseDetails::class)
            ])->andReturn($databaseMock);
        });

        $job = new MigrateServerDatabase($server, $username, $uniqueTaskId, $database);

        $job->handle($databaseFactoryMock);

        Event::assertDispatched(TaskCreated::class, 1);
        Event::assertDispatched(TaskStarted::class, 1);
        Event::assertDispatched(TaskProgress::class, 1);
        Event::assertDispatched(TaskFinished::class, 1);
        Event::assertNotDispatched(TaskFailed::class);
    }

    /**
     * @dataProvider dataProvider
     */
    public function test_failed(string $database)
    {
        Event::fake();

        $server = Server::factory()->create();
        $username = fake()->userName;
        $uniqueTaskId = (string) Str::uuid();

        $databaseMock = Mockery::mock(Database::class, function(MockInterface $mock) {
            $mock->shouldReceive('migrate')->once()->andReturn(1);
        });

        $databaseFactoryMock = Mockery::mock(DatabaseFactory::class, function(MockInterface $mock) use($databaseMock, $database) {
            $mock->shouldReceive('make')->once()->withArgs([
                $database,
                IsInstanceOf::anInstanceOf(DatabaseDetails::class)
            ])->andReturn($databaseMock);
        });

        $job = new MigrateServerDatabase($server, $username, $uniqueTaskId, $database);

        $job->handle($databaseFactoryMock);

        Event::assertDispatched(TaskCreated::class, 1);
        Event::assertDispatched(TaskStarted::class, 1);
        Event::assertDispatched(TaskProgress::class, 1);
        Event::assertDispatched(TaskFailed::class, 1);
        Event::assertNotDispatched(TaskFinished::class);
    }
}
