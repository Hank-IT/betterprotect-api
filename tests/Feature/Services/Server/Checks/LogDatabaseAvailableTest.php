<?php

namespace Tests\Feature\Services\Server\Checks;

use App\Services\Server\Checks\LogDatabaseAvailable;
use App\Services\Server\Database;
use App\Services\Server\dtos\DatabaseDetails;
use App\Services\Server\Models\Server;
use Hamcrest\Core\IsInstanceOf;
use Mockery;
use App\Services\Server\Factories\DatabaseFactory;
use Mockery\MockInterface;
use Tests\TestCase;

class LogDatabaseAvailableTest extends TestCase
{
    public function test_available()
    {
        $server = Server::factory()->create();

        $databaseMock = Mockery::mock(Database::class, function (MockInterface $mock) {
            $mock->shouldReceive('available')->once()->andReturnTrue();
        });

        $this->mock(DatabaseFactory::class, function(MockInterface $mock) use($databaseMock) {
            $mock->shouldReceive('make')->once()->withArgs([
                'log', IsInstanceOf::anInstanceOf(DatabaseDetails::class),
            ])->andReturn($databaseMock);
        });

        $this->assertTrue(app(LogDatabaseAvailable::class)->getState($server));
    }

    public function test_unavailable()
    {
        $server = Server::factory()->create();

        $databaseMock = Mockery::mock(Database::class, function (MockInterface $mock) {
            $mock->shouldReceive('available')->once()->andReturnFalse();
        });

        $this->mock(DatabaseFactory::class, function(MockInterface $mock) use($databaseMock) {
            $mock->shouldReceive('make')->once()->withArgs([
                'log', IsInstanceOf::anInstanceOf(DatabaseDetails::class),
            ])->andReturn($databaseMock);
        });

        $this->assertFalse(app(LogDatabaseAvailable::class)->getState($server));
    }

    public function test_get_key()
    {
        $this->assertEquals(
            'log-database-available',
            app(LogDatabaseAvailable::class)->getKey(),
        );
    }
}
