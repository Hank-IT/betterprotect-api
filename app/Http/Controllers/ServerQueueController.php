<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Exceptions\ErrorException;
use App\Postfix\Queue;
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
    public function index(Server $server)
    {
        $mails = (new Queue($server))->get();

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
    public function store(Server $server)
    {
        $output = (new Queue($server))->flush();

        return response()->json([
            'status' => 'success',
            'message' => 'Mail Queue erfolgreich geflushed.',
            'data' =>$output,
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
    public function destroy(Server $server, $queueId)
    {
       $output = (new Queue($server))->deleteMail($queueId);

        return response()->json([
            'status' => 'success',
            'message' => 'Mail erfolgreich gelöscht.',
            'data' => $output,
        ]);
    }
}
