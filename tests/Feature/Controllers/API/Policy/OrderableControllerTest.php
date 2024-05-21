<?php

namespace Tests\Feature\Controllers\API\Policy;

use App\Services\Milter\Models\MilterException;
use Mockery;
use App\Services\Authentication\Models\User;
use App\Services\Order\Actions\MoveItemDown;
use App\Services\Order\Actions\MoveItemUp;
use App\Services\Rules\Models\ClientSenderAccess;
use Closure;
use Mockery\MockInterface;
use Tests\TestCase;

class OrderableControllerTest extends TestCase
{
    public static function dataProvider()
    {
        return [
            [
                'client-sender-access',
                'up',
                ClientSenderAccess::class,
                function() {
                    return ClientSenderAccess::factory()->create();
                }
            ],
            [
                'client-sender-access',
                'down',
                ClientSenderAccess::class,
                function() {
                    return ClientSenderAccess::factory()->create();
                }
            ],
            [
                'milter-exception',
                'up',
                MilterException::class,
                function() {
                    return MilterException::factory()->create();
                }
            ],
            [
                'milter-exception',
                'down',
                MilterException::class,
                function() {
                    return MilterException::factory()->create();
                }
            ],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function test(string $type, string $mode, string $modelClass, Closure $callback)
    {
        $user = User::factory()->create();

        $this->be($user);

        $model = $callback();

        if ($mode === 'up') {
            $this->mock(MoveItemUp::class, function(MockInterface $mock) use($model, $modelClass) {
                $mock->shouldReceive('execute')->once()->withArgs([
                    Mockery::on(function($arg) use($model, $modelClass) {
                        return $arg instanceof $modelClass && $arg->getKey() === $model->getKey();
                    })
                ]);
            });

            $this->mock(MoveItemDown::class, function(MockInterface $mock) {
                $mock->shouldReceive('execute')->never();
            });
        }

        if ($mode === 'down') {
            $this->mock(MoveItemDown::class, function(MockInterface $mock) use($model, $modelClass) {
                $mock->shouldReceive('execute')->once()->withArgs([
                    Mockery::on(function($arg) use($model, $modelClass) {
                        return $arg instanceof $modelClass && $arg->getKey() === $model->getKey();
                    })
                ]);
            });

            $this->mock(MoveItemUp::class, function(MockInterface $mock) {
                $mock->shouldReceive('execute')->never();
            });
        }

        $this->patchJson(route('api.v1.order.store', [
            'orderableEntitiesEnum' => $type,
            'mode' => $mode,
            'id' => $model->getKey(),
        ]))->assertSuccessful();
    }

    public function test_it_fails_due_to_invalid_mode()
    {
        $user = User::factory()->create();

        $this->be($user);

        $model = ClientSenderAccess::factory()->create();

        $this->patchJson(route('api.v1.order.store', [
            'orderableEntitiesEnum' => 'client-sender-access',
            'mode' => 'fix',
            'id' => $model->getKey(),
        ]))->assertStatus(422);
    }
}
