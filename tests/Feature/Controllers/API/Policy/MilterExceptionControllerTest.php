<?php

namespace Tests\Feature\Controllers\API\Policy;

use App\Services\Authentication\Models\User;
use App\Services\Milter\Actions\CreateMilterExceptionForMilter;
use App\Services\Milter\Actions\DeleteMilterException;
use App\Services\Milter\Actions\ValidateMilterExceptionClient;
use App\Services\Milter\Models\Milter;
use App\Services\Milter\Models\MilterException;
use App\Services\Order\Actions\FixItemOrder;
use Mockery\MockInterface;
use Tests\TestCase;

class MilterExceptionControllerTest extends TestCase
{
    public function testIndex()
    {
        $user = User::factory()->create();

        $this->be($user);

        MilterException::factory()->has(Milter::factory())->count(5)->create();

        $this->getJson(route('api.v1.milter.exception.index'))
            ->assertSuccessful()
            ->assertJsonCount(5, 'data');
    }

    public function testStore()
    {
        $user = User::factory()->create();

        $this->be($user);

        $milter = Milter::factory()->create();

        $milterException = MilterException::factory()->create();

        $this->mock(ValidateMilterExceptionClient::class, function(MockInterface $mock) use($milterException) {
            $mock->shouldReceive('execute')->once()->withArgs([
                $milterException->client_type, $milterException->client_payload,
            ]);
        });

        $this->mock(CreateMilterExceptionForMilter::class, function(MockInterface $mock) use($milterException, $milter) {
            $mock->shouldReceive('execute')->once()->withArgs([
                $milterException->client_type,
                $milterException->client_payload,
                \Mockery::on(function($arg) use($milter) {
                    return $arg[0] === $milter->getKey();
                }),
                $milterException->description,
            ])->andReturn($milterException);
        });

        $this->postJson(route('api.v1.milter.exception.store'), [
            'client_type' => $milterException->client_type,
            'client_payload' => $milterException->client_payload,
            'description' => $milterException->description,
            'milter_id' => [$milter->getKey()],
        ])->assertSuccessful()->assertJsonPath('data.id', $milterException->getKey());
    }

    public function testDestroy()
    {
        $user = User::factory()->create();

        $this->be($user);

        $milterException = MilterException::factory()->create();

        $this->mock(DeleteMilterException::class, function(MockInterface $mock) use($milterException) {
            $mock->shouldReceive('execute')->once()->withArgs([
                \Mockery::on(function($arg) use($milterException) {
                    return $arg instanceof MilterException && $arg->getKey() === $milterException->getKey();
                })
            ]);
        });

        $this->mock(FixItemOrder::class, function(MockInterface $mock) use($milterException) {
            $mock->shouldReceive('execute')->once()->withArgs([
                \Mockery::on(function($arg) use($milterException) {
                    return $arg instanceof MilterException && $arg->getKey() === $milterException->getKey();
                })
            ]);
        });

        $this->deleteJson(route('api.v1.milter.exception.destroy', $milterException->getKey()))
            ->assertSuccessful();
    }
}
