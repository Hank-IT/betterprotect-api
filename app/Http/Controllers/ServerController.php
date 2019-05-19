<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ServerController extends Controller
{
    public function index()
    {
        return Server::all();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'hostname' => 'required|string|unique:servers',
            'description' => 'string',
            'db_host' => 'required|string',
            'db_name' => 'required|string',
            'db_user' => 'required|string',
            'db_password' => 'required|string',
            'db_port' => 'required|integer|max:65535',
        ]);

        $server = Server::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Eintrag wurde erfolgreich hinzugefügt.',
            'data' => $server,
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request, Server $server)
    {
        $this->validate($request, [
            'hostname' => 'required|string',
            'description' => 'nullable|string',
            'db_host' => 'required|string',
            'db_name' => 'required|string',
            'db_user' => 'required|string',
            'db_password' => 'nullable|string',
        ]);

        $server->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Server wurde erfolgreich aktualisiert.',
            'data' => [],
        ]);
    }

    public function show(Server $server)
    {
        return $server;
    }

    public function destroy(Server $server)
    {
        $server->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Server wurde erfolgreich entfernt.',
            'data' => [],
        ]);
    }
}
