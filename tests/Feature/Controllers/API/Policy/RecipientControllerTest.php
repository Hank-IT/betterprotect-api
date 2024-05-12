<?php

namespace Tests\Feature\Controllers\API\Policy;

use App\Services\Authentication\Models\User;
use App\Services\Recipients\Actions\FirstOrCreateRelayRecipient;
use App\Services\Recipients\Actions\DeleteRelayRecipient;
use App\Services\Recipients\Actions\PaginateRecipients;
use App\Services\Recipients\Models\RelayRecipient;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery\MockInterface;
use Tests\TestCase;

class RecipientControllerTest extends TestCase
{
    public function testIndex()
    {
        $user = User::factory()->create();

        $this->be($user);

        $recipient = RelayRecipient::factory()->create();

        $paginator = new LengthAwarePaginator([
            $recipient
        ], 1, 5);

        $this->mock(PaginateRecipients::class, function(MockInterface $mock) use($paginator) {
            $mock->shouldReceive('execute')->once()->withArgs([
                1, 5, 'test',
            ])->andReturn($paginator);
        });

        $this->getJson(route('api.v1.recipients.index', [
            'page_number' => 1,
            'page_size' => 5,
            'search' => 'test'
        ]))->assertSuccessful()->assertJsonPath('data.0.id', $recipient->getKey());
    }

    public function testStore()
    {
        $user = User::factory()->create();

        $this->be($user);

        $recipient = RelayRecipient::factory()->create();

        $email = fake()->email;

        $this->mock(FirstOrCreateRelayRecipient::class, function(MockInterface $mock) use($recipient, $email) {
            $mock->shouldReceive('execute')->once()->withArgs([
                $email,
                'local',
            ])->andReturn($recipient);
        });

        $this->postJson(route('api.v1.recipients.store'), [
            'payload' => $email,
        ])->assertSuccessful();
    }

    public function testDestroy()
    {
        $user = User::factory()->create();

        $this->be($user);

        $deletableRecipients = RelayRecipient::factory()->count(2)->create();

        $this->assertModelExists($deletableRecipients[0]);
        $this->assertModelExists($deletableRecipients[1]);

        $retainedRecipient = RelayRecipient::factory()->create();

        $this->assertModelExists($retainedRecipient);

        $this->mock(DeleteRelayRecipient::class, function(MockInterface $mock) use($deletableRecipients, $retainedRecipient) {
            $mock->shouldReceive('execute')->once()->withArgs([
                \Mockery::on(function($arg) use($deletableRecipients) {
                    return $arg->getKey() === $deletableRecipients[0]->getKey();
                })
            ]);

            $mock->shouldReceive('execute')->once()->withArgs([
                \Mockery::on(function($arg) use($deletableRecipients) {
                    return $arg->getKey() === $deletableRecipients[1]->getKey();
                })
            ]);

            $mock->shouldReceive('execute')->once()->withArgs([
                \Mockery::on(function($arg) use($retainedRecipient) {
                    return $arg->getKey() === $retainedRecipient->getKey();
                })
            ])->never();
        });

        $this->deleteJson(route('api.v1.recipients.destroy'), [
            'ids' => [
                $deletableRecipients[0]->getKey(),
                $deletableRecipients[1]->getKey(),
            ]
        ])->assertSuccessful();
    }
}
