<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Exceptions\ErrorException;
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
        if (empty($server->sudo)) {
            throw new ErrorException('Terminalzugriff ist nicht konfiguriert.');
        }

        $console = $server->console();

        $console->sudo($server->sudo)
            ->bin($server->postqueue)
            ->param('-j')
            ->exec();

        if ($console->getExitStatus() !== 0) {
            throw new ErrorException;
        }

        $output = $console->getOutput();

        // each mail is its own json object
        $output = explode("\n", $output);

        // remove last newline
        array_pop($output);

        $mails = [];

        foreach($output as $mail) {
            $mails[] = json_decode($mail, true);
        }

        return response()->json([
            'status' => 'success',
            'message' => null,
            'data' => $mails,
        ]);
    }

    public function store(Server $server)
    {
        if (empty($server->sudo)) {
            throw new ErrorException('Terminalzugriff ist nocht konfiguriert.');
        }

        $console = $server->console();

        $console->sudo($server->sudo)
            ->bin($server->postqueue)
            ->param('-f')
            ->exec();

        if ($console->getExitStatus() !== 0) {
            throw new ErrorException;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Mail Queue erfolgreich geflushed.',
            'data' => $console->getOutput(),
        ]);
    }

    public function destroy(Server $server, $queueId)
    {
        if (empty($server->sudo)) {
            throw new ErrorException('Terminalzugriff ist nocht konfiguriert.');
        }

        $console = $server->console();

        $console->sudo($server->sudo)
            ->bin($server->postsuper)
            ->param('-d')
            ->param($queueId)
            ->exec();

        if ($console->getExitStatus() !== 0) {
            throw new ErrorException;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Mail erfolgreich gelöscht.',
            'data' => $console->getOutput(),
        ]);
    }
}
