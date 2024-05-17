<?php

namespace App\Services\PostfixQueue\Jobs;

use App\Services\PostfixQueue\Actions\GetPostfixQueueEntriesFromServer;
use App\Services\PostfixQueue\Actions\StorePostfixQueueInCache;
use App\Services\Server\Actions\GetServerStateFromCache;
use App\Services\Server\Models\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetPostfixQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(
        GetPostfixQueueEntriesFromServer $getPostfixQueueEntriesFromServer,
        StorePostfixQueueInCache $storePostfixQueueInCache,
        GetServerStateFromCache $getServerStateFromCache,
    ): void {
        Server::all()->each(function (Server $server) use(
            $getPostfixQueueEntriesFromServer, $storePostfixQueueInCache, $getServerStateFromCache
        ) {
            $state = $getServerStateFromCache->execute($server->hostname);

            if ($state->getSshConnectionState()) {
                $storePostfixQueueInCache->execute(
                    $server->hostname, $getPostfixQueueEntriesFromServer->execute($server)
                );
            }
        });
    }
}
