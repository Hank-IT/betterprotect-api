<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;
use App\Exceptions\ErrorException;

class ServerTerminalController extends Controller
{
    public function store(Server $server, Request $request)
    {
        $this->validate($request, [
            'user' => 'required|string',
            'public_key' => 'required|string',
            'private_key' => 'string',
            'postqueue' => 'required|string',
            'postsuper' => 'required|string',
            'sudo' => 'required|string',
        ]);

        $server->forceFill([
            'user' => $request->user,
            'public_key' => $request->public_key,
            'postqueue' => $request->postqueue,
            'sudo' => $request->sudo,
            'postsuper' => $request->postsuper,
        ]);

        if ($request->filled('private_key')) {
            $server->private_key = $request->private_key;
        }

        $console = $server->console();

        $console->bin($request->sudo)->param('--help')->exec();

        if ($console->getExitStatus() !== 0) {
            throw new ErrorException('Sudo konnte nicht ausgeführt werden!');
        }

        $server->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Einstellungen erfolgreich gespeichert.',
            'data' => [],
        ]);
    }

    public function show(Server $server)
    {
        return response()->json([
            'status' => 'success',
            'message' => null,
            'data' => $server,
        ]);
    }
}
