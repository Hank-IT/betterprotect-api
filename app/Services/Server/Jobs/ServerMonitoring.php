<?php

namespace App\Services\Server\Jobs;

use App\Services\Server\Actions\GetServerState;
use App\Services\Server\Actions\StoreServerStateInCache;
use App\Services\Server\Events\ServerMonitored;
use App\Services\Server\Models\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ServerMonitoring implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $checks = [
        \App\Services\Server\Checks\LogDatabaseAvailable::class,
        \App\Services\Server\Checks\PostfixDatabaseAvailable::class,
        \App\Services\Server\Checks\SshConnection::class,
        \App\Services\Server\Checks\SudoExecutable::class,
        \App\Services\Server\Checks\PostsuperExecutable::class,
        \App\Services\Server\Checks\PostqueueExecutable::class,
    ];

    public function handle(
        GetServerState $getServerState,
        StoreServerStateInCache $storeServerStateInCache,
    ): void {
        Server::all()->each(function (Server $server) use($getServerState, $storeServerStateInCache) {
            $state = $getServerState->execute($server, $this->checks);

            $storeServerStateInCache->execute(
                $server->hostname, $getServerState->execute($server, $this->checks)
            );

            ServerMonitored::dispatch($state);
        });
    }
}
