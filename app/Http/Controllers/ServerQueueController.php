<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Models\Server;
use App\Services\MailLogging\LegacyPostfixParser\Queue;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use MrCrankHank\ConsoleAccess\Exceptions\PublicKeyMismatchException;

class ServerQueueController extends Controller
{
    /**
     * @param Server $server
     * @return \Illuminate\Http\JsonResponse
     * @throws ErrorException
     * @throws \MrCrankHank\ConsoleAccess\Exceptions\MissingCommandException
     * @throws PublicKeyMismatchException
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'currentPage' => 'required|int',
            'perPage' => 'required|int',
        ]);

        $mails = [];
        Server::where('ssh_feature_enabled', '=', true)->get()->each(function($server) use(&$mails) {
            $mails[] = app(Queue::class, ['server' => $server])->get();
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

    /**
     * @param Server $server
     * @return \Illuminate\Http\JsonResponse
     * @throws ErrorException
     */
    public function store()
    {
        Server::where('ssh_feature_enabled')->get()->each(function($server) {
            app(Queue::class, ['server' => $server])->flush();
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Mail Queue erfolgreich geflushed.',
            'data' => null,
        ]);
    }

    /**
     * @param Server $server
     * @param $queueId
     * @return \Illuminate\Http\JsonResponse
     * @throws ErrorException
     * @throws PublicKeyMismatchException
     * @throws \MrCrankHank\ConsoleAccess\Exceptions\MissingCommandException
     */
    public function destroy(Request $request, Server $server)
    {
        $this->validate($request, [
            'queue_id' => 'required|string'
        ]);

        $output = app(Queue::class, ['server' => $server])->deleteMail($request->queue_id);

        return response()->json([
            'status' => 'success',
            'message' => 'Mail erfolgreich gelöscht.',
            'data' => $output,
        ]);
    }
}
