<?php

namespace Tests\Feature\Controllers\API;

use App\Services\Authentication\Models\User;
use App\Services\BetterprotectPolicy\Jobs\BetterprotectPolicyInstallation;
use App\Services\Server\Models\Server;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class BetterprotectPolicyControllerTest extends TestCase
{
    public function test()
    {
        Queue::fake();

        $user = User::factory()->create();
        $server = Server::factory()->create();

        $this->be($user);

        $this->postJson(route('api.v1.policy.installation'), [
            'server_id' => [$server->getKey()]
        ])->assertSuccessful();

        Queue::assertPushed(BetterprotectPolicyInstallation::class);
    }
}
