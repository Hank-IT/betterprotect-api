<?php

namespace App\Http\Controllers\API;

use App\Services\PostfixQueue\Actions\GetPostfixQueueEntriesFromCache;
use App\Services\Server\Models\Server;

class PostfixQueueCountController
{
    public function __invoke(Server $server, GetPostfixQueueEntriesFromCache $getPostfixQueueEntriesFromCache)
    {
        $entries = $getPostfixQueueEntriesFromCache->execute($server->hostname);

        return response()->json([
            'data' => [
                'count' => is_countable($entries) ? count($entries): null,
            ]
        ]);
    }
}
