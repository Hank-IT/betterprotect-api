<?php

namespace Tests\Feature\Controllers\API\Policy;

use App\Services\Authentication\Models\User;
use App\Services\Recipients\Actions\GetIgnoredLdapDomains;
use App\Services\Recipients\Jobs\RefreshLdapRecipients;
use App\Services\Tasks\Events\TaskCreated;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Mockery\MockInterface;
use Tests\TestCase;

class RecipientLdapControllerTest extends TestCase
{
    public function test()
    {
        Event::fake();
        Queue::fake();

        $user = User::factory()->create();

        $this->be($user);

        $this->mock(GetIgnoredLdapDomains::class, function(MockInterface $mock) {
            $mock->shouldReceive('execute')->once()->andReturn([]);
        });

        $this->postJson(route('api.v1.recipients.ldap'))->assertSuccessful();

        Queue::assertPushed(RefreshLdapRecipients::class);
        Event::assertDispatched(TaskCreated::class);
    }
}