<?php

namespace App\Http\Controllers\API;

use App\Services\PostfixQueue\Actions\GetPostfixQueueEntriesFromCache;
use App\Services\Server\Models\Server;

class PostfixQueueCountController
{
    public function __invoke(Server $server, GetPostfixQueueEntriesFromCache $getPostfixQueueEntriesFromCache)
    {
        return response()->json([
            'data' => [
                'count' => count($getPostfixQueueEntriesFromCache->execute($server->hostname)),
            ]
        ]);
    }
}
