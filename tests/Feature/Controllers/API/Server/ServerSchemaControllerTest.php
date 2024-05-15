<?php

namespace Tests\Feature\Controllers\API\Server;

use App\Services\Server\Factories\DatabaseFactory;
use Mockery\MockInterface;
use App\Services\Server\Database;
use Mockery;
use App\Services\Authentication\Models\User;
use App\Services\Server\Jobs\MigrateServerDatabase;
use App\Services\Server\Models\Server;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ServerSchemaControllerTest extends TestCase
{
    public static function dataProvider()
    {
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
    public function test_store(string $database)
    {
        Queue::fake();

        $user = User::factory()->create();

        $this->be($user);

        $server = Server::factory()->create();

        $this->postJson(route('api.v1.server.schema.check', $server->getKey()), [
            'database' => $database,
        ])->assertSuccessful();

        Queue::assertPushed(MigrateServerDatabase::class);
    }

    /**
     * @dataProvider dataProvider
     */
    public function test_show_without_migration(string $database)
    {
        $user = User::factory()->create();

        $this->be($user);

        $server = Server::factory()->create();

        $databaseMock = Mockery::mock(Database::class, function(MockInterface $mock) {
            $mock->shouldReceive('available')->once()->andReturnTrue();
            $mock->shouldReceive('needsMigrate')->once()->andReturnFalse();
        });

        $this->mock(DatabaseFactory::class, function(MockInterface $mock) use($databaseMock) {
            $mock->shouldReceive('make')->once()->andReturn($databaseMock);
        });

        $this->getJson(route('api.v1.server.schema.check', ['server' => $server->getKey(), 'database' => $database]))
            ->assertSuccessful()
            ->assertJsonPath('data.database', $database)
            ->assertJsonPath('data.available', true)
            ->assertJsonPath('data.needs-migration', false);
    }

    /**
     * @dataProvider dataProvider
     */
    public function test_show_with_migration(string $database)
    {
        $user = User::factory()->create();

        $this->be($user);

        $server = Server::factory()->create();

        $databaseMock = Mockery::mock(Database::class, function(MockInterface $mock) {
            $mock->shouldReceive('available')->once()->andReturnTrue();
            $mock->shouldReceive('needsMigrate')->once()->andReturnTrue();
        });

        $this->mock(DatabaseFactory::class, function(MockInterface $mock) use($databaseMock) {
            $mock->shouldReceive('make')->once()->andReturn($databaseMock);
        });

        $this->getJson(route('api.v1.server.schema.check', ['server' => $server->getKey(), 'database' => $database]))
            ->assertSuccessful()
            ->assertJsonPath('data.database', $database)
            ->assertJsonPath('data.available', true)
            ->assertJsonPath('data.needs-migration', true);
    }

    /**
     * @dataProvider dataProvider
     */
    public function test_show_unavailable(string $database)
    {
        $user = User::factory()->create();

        $this->be($user);

        $server = Server::factory()->create();

        $databaseMock = Mockery::mock(Database::class, function(MockInterface $mock) {
            $mock->shouldReceive('available')->once()->andReturnFalse();
            $mock->shouldReceive('needsMigrate')->never();
        });

        $this->mock(DatabaseFactory::class, function(MockInterface $mock) use($databaseMock) {
            $mock->shouldReceive('make')->once()->andReturn($databaseMock);
        });

        $this->getJson(route('api.v1.server.schema.check', ['server' => $server->getKey(), 'database' => $database]))
            ->assertSuccessful()
            ->assertJsonPath('data.database', $database)
            ->assertJsonPath('data.available', false)
            ->assertJsonPath('data.needs-migration', false);
    }
}
