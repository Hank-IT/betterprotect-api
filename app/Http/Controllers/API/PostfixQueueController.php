<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\PostfixQueue\PostfixQueue;
use App\Services\Server\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PostfixQueueController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'currentPage' => 'required|int',
            'perPage' => 'required|int',
        ]);

        $mails = [];
        Server::where('ssh_feature_enabled', '=', true)->get()->each(function($server) use(&$mails) {
            $mails[] = app(PostfixQueue::class, ['server' => $server])->get();
        });

        if (empty($mails)) {
            $mails = [];
        } else {
            $mails = collect(array_merge(...$mails));

            // Paginate
            $count = $mails->count();
            $offset = ($request->currentPage-1) * $request->perPage;
            $mails = array_slice($mails->toArray(), $offset, $request->perPage);
            $mails = new LengthAwarePaginator($mails, $count, $request->perPage, $request->currentPage);
        }

        return response()->json([
            'status' => 'success',
            'message' => null,
            'data' => $mails,
        ]);
    }

    public function store()
    {
        Server::where('ssh_feature_enabled')->get()->each(function($server) {
            app(PostfixQueue::class, ['server' => $server])->flush();
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Mail Queue erfolgreich geflushed.',
            'data' => null,
        ]);
    }

    public function destroy(Request $request, Server $server)
    {
        $this->validate($request, [
            'queue_id' => 'required|string'
        ]);

        $output = app(PostfixQueue::class, ['server' => $server])->deleteMail($request->queue_id);

        return response()->json([
            'status' => 'success',
            'message' => 'Mail erfolgreich gelöscht.',
            'data' => $output,
        ]);
    }
}
