<?php

namespace Tests\Feature\Services\BetterprotectPolicy\Actions;

use App\Services\BetterprotectPolicy\Actions\InstallPolicy;
use App\Services\BetterprotectPolicy\Contracts\BetterprotectPolicyDataRetriever;
use App\Services\BetterprotectPolicy\Contracts\BetterprotectPolicyDto;
use App\Services\Server\Database;
use App\Services\Server\dtos\DatabaseDetails;
use App\Services\Server\Factories\DatabaseFactory;
use App\Services\Server\Models\Server;
use App\Services\Tasks\Events\TaskFailed;
use App\Services\Tasks\Events\TaskFinished;
use App\Services\Tasks\Events\TaskProgress;
use App\Services\Tasks\Events\TaskStarted;
use Hamcrest\Core\IsInstanceOf;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Mockery;
use App\Services\BetterprotectPolicy\BetterprotectPolicy;
use Mockery\MockInterface;
use Tests\TestCase;

class InstallPolicyTest extends TestCase
{
    public function test_successful()
    {
        Event::fake();

        $server = Server::factory()->create();

        $uniqueTaskId = (string) Str::uuid();

        $data = [
            'id' => (string) Str::uuid(),
        ];

        $table = fake()->word;
        $description = fake()->text;

        $dataRetrieverMock = Mockery::mock(BetterprotectPolicyDataRetriever::class, function(MockInterface $mock) use($data) {
            $mock->shouldReceive('get')->once()->andReturn($data);
        });

        $policyDtoMock = Mockery::mock(BetterprotectPolicyDto::class, function(MockInterface $mock) use($dataRetrieverMock, $table, $description) {
            $mock->shouldReceive('getDataRetriever')->once()->andReturn($dataRetrieverMock);
            $mock->shouldReceive('getTable')->once()->andReturn($table);
            $mock->shouldReceive('getDescription')->once()->andReturn($description);
        });

        $this->mock(BetterprotectPolicy::class, function(MockInterface $mock) use($policyDtoMock) {
            $mock->shouldReceive('get')->once()->andReturn([
                $policyDtoMock,
            ]);
        });

        $connectionTableMock = Mockery::mock(ConnectionInterface::class, function(MockInterface $mock) use($data) {
            $mock->shouldReceive('truncate')->once()->withNoArgs();
            $mock->shouldReceive('insert')->once()->withArgs([$data]);
        });

        $connectionMock = Mockery::mock(ConnectionInterface::class, function(MockInterface $mock) use($table, $connectionTableMock) {
            $mock->shouldReceive('beginTransaction')->once();
            $mock->shouldReceive('getPdo->exec')->once()->withArgs([sprintf('lock tables %s write', $table)]);
            $mock->shouldReceive('table')->twice()->withArgs([$table])->andReturn($connectionTableMock);
            $mock->shouldReceive('getPdo->exec')->once()->withArgs(['unlock tables']);
            $mock->shouldReceive('commit')->once();
        });

        $databaseMock = Mockery::mock(Database::class, function(MockInterface $mock) use($connectionMock) {
            $mock->shouldReceive('needsMigrate')->once()->andReturnFalse();
            $mock->shouldReceive('getConnection')->once()->andReturn($connectionMock);
        });

        $this->mock(DatabaseFactory::class, function(MockInterface $mock) use($databaseMock) {
            $mock->shouldReceive('make')->once()->withArgs([
                'postfix',
                IsInstanceOf::anInstanceOf(DatabaseDetails::class),
            ])->andReturn($databaseMock);
        });

        app(InstallPolicy::class)->execute($server, $uniqueTaskId);

        Event::assertDispatched(TaskStarted::class, 1);
        Event::assertDispatched(TaskProgress::class, 2);
        Event::assertDispatched(TaskFinished::class, 1);
    }

    public function test_failed_migration_required()
    {
        Event::fake();

        $server = Server::factory()->create();

        $uniqueTaskId = (string) Str::uuid();

        $databaseMock = Mockery::mock(Database::class, function(MockInterface $mock) {
            $mock->shouldReceive('needsMigrate')->once()->andReturnTrue();
            $mock->shouldReceive('getConnection')->never();
        });

        $this->mock(DatabaseFactory::class, function(MockInterface $mock) use($databaseMock) {
            $mock->shouldReceive('make')->once()->withArgs([
                'postfix',
                IsInstanceOf::anInstanceOf(DatabaseDetails::class),
            ])->andReturn($databaseMock);
        });

        app(InstallPolicy::class)->execute($server, $uniqueTaskId);

        Event::assertDispatched(TaskStarted::class, 1);
        Event::assertDispatched(TaskProgress::class, 1);
        Event::assertDispatched(TaskFailed::class, 1);
    }
}
