<?php

namespace Tests\Feature\Services\BetterprotectPolicy\Commands;

use App\Services\BetterprotectPolicy\Jobs\BetterprotectPolicyInstallation;
use App\Services\Server\Models\Server;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Queue;

class InstallPolicyTest extends TestCase
{
    public function test_with_server_id()
    {
        Queue::fake();

        $server = Server::factory()->create();

        $this->artisan('policy:install', [
            'server' => $server->getKey(),
        ])->assertSuccessful();

        Queue::assertPushed(BetterprotectPolicyInstallation::class);
    }

    public function test_with_server_hostname()
    {
        Queue::fake();

        $server = Server::factory()->create();

        $this->artisan('policy:install', [
            'server' => $server->hostname,
        ])->assertSuccessful();

        Queue::assertPushed(BetterprotectPolicyInstallation::class);
    }

    public function test_unknown_server()
    {
        Queue::fake();

        $this->artisan('policy:install', [
            'server' => 'testing',
        ])->assertFailed();

        Queue::assertNotPushed(BetterprotectPolicyInstallation::class);
    }
}
