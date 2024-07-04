<?php

namespace Tests\Feature\Controllers\API\Policy;

use App\Services\Authentication\Models\User;
use App\Services\Rules\Actions\CreateClientSenderAccess;
use App\Services\Rules\Actions\DeleteClientSenderAccess;
use App\Services\Rules\Actions\GetClientSenderAccess;
use App\Services\Rules\Actions\ValidateClient;
use App\Services\Rules\Actions\ValidateSender;
use App\Services\Rules\Models\ClientSenderAccess;
use App\Services\Rules\Resources\ClientSenderAccessResource;
use Illuminate\Database\Eloquent\Collection;
use Mockery\MockInterface;
use Tests\TestCase;

class RuleControllerTest extends TestCase
{
    public function testIndex()
    {
        $user = User::factory()->create();

        $this->be($user);

        $clientSenderAccess = ClientSenderAccess::factory()->create();

        $this->mock(GetClientSenderAccess::class, function(MockInterface $mock) use($clientSenderAccess) {
            $mock->shouldReceive('execute')->andReturn(new Collection([
                $clientSenderAccess
            ]));
        });

        $this->getJson(route('api.v1.rule.index'))->assertSuccessful()->assertJsonCount(1, 'data');
    }

    public function testIndexWithSearch()
    {
        $user = User::factory()->create();

        $this->be($user);

        $clientSenderAccess = ClientSenderAccess::factory()->create();

        $search = fake()->email;

        $this->mock(GetClientSenderAccess::class, function(MockInterface $mock) use($clientSenderAccess, $search) {
            $mock->shouldReceive('execute')->withArgs([$search])->andReturn(new Collection([
                $clientSenderAccess
            ]));
        });

        $this->getJson(route('api.v1.rule.index', [
            'search' => $search,
        ]))->assertSuccessful()->assertJsonCount(1, 'data');
    }

    public function testStore()
    {
        $user = User::factory()->create();

        $this->be($user);

        $props = ClientSenderAccess::factory()->make();

        $model = new ClientSenderAccess(['id' => 'dummy']);

        $this->mock(CreateClientSenderAccess::class, function(MockInterface $mock) use($props, $model) {
            $mock->shouldReceive('execute')->once()->withArgs([
                $props->client_type,
                $props->client_payload,
                $props->sender_type,
                $props->sender_payload,
                $props->action,
                $props->message,
                $props->description,
            ])->andReturn($model);
        });

        $this->mock(ValidateClient::class, function(MockInterface $mock) use($props) {
            $mock->shouldReceive('execute')->once()->withArgs([
                $props->client_type,
                $props->client_payload,
            ]);
        });

        $this->mock(ValidateSender::class, function(MockInterface $mock) use($props) {
            $mock->shouldReceive('execute')->once()->withArgs([
                $props->sender_type,
                $props->sender_payload,
            ]);
        });

        $this->postJson(route('api.v1.rule.store'), [
            'client_type' => $props->client_type,
            'client_payload' => $props->client_payload,
            'sender_type' => $props->sender_type,
            'sender_payload' => $props->sender_payload,
            'message' => $props->message,
            'description' => $props->description,
            'action' => $props->action,
        ])->assertSuccessful()->assertJsonPath('data.id', $model->id);
    }

    public function testDestroy()
    {
        $user = User::factory()->create();

        $this->be($user);

        $model = ClientSenderAccess::factory()->create();

        $this->mock(DeleteClientSenderAccess::class, function(MockInterface $mock) use($model) {
            $mock->shouldReceive('execute')->once()->withArgs([
                \Mockery::on(function($arg) use($model) {
                    return $arg instanceof ClientSenderAccess && $arg->getKey() === $model->getKey();
                })
            ]);
        });

        $this->deleteJson(route('api.v1.rule.destroy', $model->getKey()))->assertSuccessful();
    }
}
