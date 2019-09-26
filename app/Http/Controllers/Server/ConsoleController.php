<?php

namespace App\Http\Controllers\Server;

use App\Models\Server;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class ConsoleController extends Controller
{
    public function store(Request $request, Server $server)
    {
        $this->validate($request, [
            'ssh_user' => 'required|string',
            'ssh_public_key' => 'required|string',
            'ssh_private_key' => 'required|string',
            'ssh_command_sudo' => 'required|string',
            'ssh_command_postqueue' => 'required|string',
            'ssh_command_postsuper' => 'required|string',
        ]);

        // ToDo: Connection test

        $server->update([
            'ssh_user' => $request->ssh_user,
            'ssh_public_key' => $request->ssh_public_key,
            'ssh_private_key' => $request->ssh_private_key,
            'ssh_command_sudo' => $request->ssh_command_sudo,
            'ssh_command_postqueue' => $request->ssh_command_postqueue,
            'ssh_command_postsuper' => $request->ssh_command_postsuper,
            'ssh_feature_enabled_at' => true,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Postfix Feature erfolgreich aktiviert..',
            'data' => $server
        ], Response::HTTP_OK);
    }
}
