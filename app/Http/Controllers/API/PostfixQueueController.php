<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Pagination\Actions\PaginateArray;
use App\Services\PostfixQueue\Actions\DeleteMailFromPostfixQueue;
use App\Services\PostfixQueue\Actions\FindMailinQueue;
use App\Services\PostfixQueue\Actions\FlushPostfixQueue;
use App\Services\PostfixQueue\Actions\GetPostfixQueueEntriesFromCache;
use App\Services\PostfixQueue\Actions\RefreshPostfixQueueCacheForServer;
use App\Services\PostfixQueue\Resources\PostfixQueueEntryResource;
use App\Services\Server\Models\Server;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostfixQueueController extends Controller
{
    public function index(
        Server                          $server,
        Request                         $request,
        GetPostfixQueueEntriesFromCache $getPostfixQueueEntriesFromCache,
        PaginateArray                   $paginateArray,
    ) {
        $data = $request->validate([
            'page_number' => ['required', 'integer'],
            'page_size' => ['required', 'integer'],
        ]);

        $entries = $getPostfixQueueEntriesFromCache->execute($server->hostname);

        return PostfixQueueEntryResource::collection(
            $paginateArray->execute($entries, $data['page_number'], $data['page_size']),
        );
    }

    public function show(Server $server, string $queueId, FindMailinQueue $findMailinQueue)
    {
        $entry = $findMailinQueue->execute($server, $queueId);

        if (is_null($entry)) {
            throw new NotFoundHttpException;
        }

        return new PostfixQueueEntryResource($entry);
    }

    public function store(
        Server $server,
        FlushPostfixQueue $flushPostfixQueue,
        RefreshPostfixQueueCacheForServer $refreshPostfixQueueCacheForServer
    ) {
        $flushPostfixQueue->execute($server->getSSHDetails());

        $refreshPostfixQueueCacheForServer->execute($server);

        return response(status: 200);
    }

    public function destroy(
        Request $request,
        Server $server,
        DeleteMailFromPostfixQueue $deleteMailFromPostfixQueue,
        RefreshPostfixQueueCacheForServer $refreshPostfixQueueCacheForServer
    ){
        $data = $request->validate([
            'queue_id' => ['required', 'string'],
        ]);

        $deleteMailFromPostfixQueue->execute($server->getSSHDetails(), $data['queue_id']);

        $refreshPostfixQueueCacheForServer->execute($server);

        return response(status: 200);
    }
}
