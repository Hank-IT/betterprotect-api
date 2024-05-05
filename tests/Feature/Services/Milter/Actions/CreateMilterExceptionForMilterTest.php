<?php

namespace Tests\Feature\Services\Milter\Actions;

use Mockery;
use App\Services\Milter\Actions\CreateMilterException;
use App\Services\Milter\Actions\CreateMilterExceptionForMilter;
use App\Services\Milter\Actions\SyncMilterExceptionsWithMilters;
use App\Services\Milter\Models\Milter;
use App\Services\Milter\Models\MilterException;
use App\Services\Order\Actions\FixItemOrder;
use Mockery\MockInterface;
use Tests\TestCase;

class CreateMilterExceptionForMilterTest extends TestCase
{
    public function test()
    {
        $milterException = MilterException::factory()->create();

        $milters = Milter::factory()->count(2)->create();

        $this->mock(CreateMilterException::class, function(MockInterface $mock) use ($milterException) {
            $mock->shouldReceive('execute')->once()->withArgs([
                $milterException->client_type,
                $milterException->client_payload,
                $milterException->description,
            ])->andReturn($milterException);
        });

        $this->mock(FixItemOrder::class, function(MockInterface $mock) use ($milterException) {
            $mock->shouldReceive('execute')->once()->withArgs([
                Mockery::on(function($arg) use($milterException) {
                    return $arg instanceof MilterException && $arg->getKey() === $milterException->getKey();
                })
            ]);
        });

        $this->mock(SyncMilterExceptionsWithMilters::class, function(MockInterface $mock) use($milterException, $milters) {
            $mock->shouldReceive('execute')->once()->withArgs([
                Mockery::on(function($arg) use($milterException) {
                    return $arg instanceof MilterException && $arg->getKey() === $milterException->getKey();
                }),
                Mockery::on(function($arg) use($milters) {
                    return $arg[0] === $milters[0]->getKey() && $arg[1] === $milters[1]->getKey();
                })
            ]);
        });

        app(CreateMilterExceptionForMilter::class)->execute(
            $milterException->client_type,
            $milterException->client_payload,
            $milters->pluck('id')->toArray(),
            $milterException->description,
        );
    }
}
