<?php

namespace App\Http\Controllers\Server;

use App\Models\Server;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class ServerController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'hostname' => 'required|string|unique:servers',
            'description' => 'nullable|string',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Server wurde erfolgreich hinzugefügt.',
            'data' => Server::create(['hostname' => $request->hostname, 'description' => $request->description]),
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request, Server $server)
    {
        $this->validate($request, [
            'hostname' => ['required', 'string', Rule::unique('servers')->ignore($server->id)],
            'description' => 'nullable|string',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Server wurde erfolgreich aktualisiert.',
            'data' => $server->update(['hostname' => $request->hostname, 'description' => $request->description]),
        ]);
    }

    public function show(Server $server)
    {
        return response()->json([
            'status' => 'success',
            'message' => null,
            'data' => $server->only(['hostname', 'description']),
        ]);
    }
}
