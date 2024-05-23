<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Pagination\Actions\PaginateArray;
use App\Services\PostfixQueue\Actions\DeleteMailFromPostfixQueue;
use App\Services\PostfixQueue\Actions\FlushPostfixQueue;
use App\Services\PostfixQueue\Actions\GetPostfixQueueEntriesFromCache;
use App\Services\PostfixQueue\Resources\PostfixQueueEntry;
use App\Services\Server\Models\Server;
use Illuminate\Http\Request;

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

        return PostfixQueueEntry::collection(
            $paginateArray->execute($entries, $data['page_number'], $data['page_size']),
        );
    }

    public function store(Server $server, FlushPostfixQueue $flushPostfixQueue)
    {
        $flushPostfixQueue->execute($server->getSSHDetails());

        return response(status: 200);
    }

    public function destroy(Request $request, Server $server, DeleteMailFromPostfixQueue $deleteMailFromPostfixQueue)
    {
        $data = $request->validate([
            'queue_id' => ['required', 'string'],
        ]);

        $deleteMailFromPostfixQueue->execute($server->getSSHDetails(), $data['queue_id']);

        return response(status: 200);
    }
}
