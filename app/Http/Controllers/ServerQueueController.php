<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Exceptions\ErrorException;
use App\Postfix\Queue;
use Illuminate\Http\Request;
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
    public function index()
    {
        $mails = [];
        Server::where('ssh_feature_enabled', '=', true)->get()->each(function($server) use(&$mails) {
            $mails[] = (new Queue($server))->get();
        });

        if (! empty($mails)) {
            $mails = array_merge(...$mails);
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
            (new Queue($server))->flush();
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

        $output = (new Queue($server))->deleteMail($request->queue_id);

        return response()->json([
            'status' => 'success',
            'message' => 'Mail erfolgreich gelöscht.',
            'data' => $output,
        ]);
    }
}
