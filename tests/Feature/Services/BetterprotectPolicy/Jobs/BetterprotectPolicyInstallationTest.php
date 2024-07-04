<?php

namespace Tests\Feature\Services\BetterprotectPolicy\Jobs;

use App\Services\BetterprotectPolicy\Actions\InstallPolicy;
use Mockery;
use Mockery\MockInterface;
use App\Services\Authentication\Models\User;
use App\Services\BetterprotectPolicy\Jobs\BetterprotectPolicyInstallation;
use App\Services\Server\Models\Server;
use App\Services\Tasks\Events\TaskCreated;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

class BetterprotectPolicyInstallationTest extends TestCase
{
    public function test()
    {
        Event::fake();

        $server = Server::factory()->create();
        $uniqueTaskId = (string) Str::uuid();
        $user = User::factory()->create();

        $installPolicyMock = Mockery::mock(InstallPolicy::class, function(MockInterface $mock) use($server, $uniqueTaskId) {
            $mock->shouldReceive('execute')->once()->withArgs([
                Mockery::on(function($arg) use($server) {
                    return $arg->getKey() === $server->getKey();
                }),
                $uniqueTaskId,
            ]);
        });

        $job = new BetterprotectPolicyInstallation($server, $uniqueTaskId, $user->username);

        $job->handle($installPolicyMock);

        Event::assertDispatched(TaskCreated::class);
    }
}
