<?php

namespace App\Http\Controllers\Server;

use App\Models\Server;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class AmavisController extends Controller
{
    public function store(Request $request, Server $server)
    {
        $this->validate($request, [
            'amavis_db_host' => 'required|string',
            'amavis_db_name' => 'required|string',
            'amavis_db_user' => 'nullable|string',
            'amavis_db_password' => 'nullable|string',
            'amavis_db_port' => 'required|int',
        ]);

        $server->fill([
            'amavis_db_host' => $request->amavis_db_host,
            'amavis_db_name' => $request->amavis_db_name,
            'amavis_db_user' => $request->amavis_db_user,
            'amavis_db_password' => $request->amavis_db_password,
            'amavis_db_port' => $request->amavis_db_port,
            'amavis_feature_enabled' => true,
        ]);

        if (! $server->amavisDatabase()->available()) {
            throw ValidationException::withMessages(['amavis_db_host' => 'Datenbank ist nicht verfügbar.']);
        }

        $server->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Amavis Feature erfolgreich aktiviert.',
            'data' => $server
        ]);
    }

    public function update(Request $request, Server $server)
    {
        $this->validate($request, [
            'amavis_db_host' => 'required|string',
            'amavis_db_name' => 'required|string',
            'amavis_db_user' => 'nullable|string',
            'amavis_db_password' => 'nullable|string',
            'amavis_db_port' => 'required|int',
            'amavis_feature_enabled' => 'required|boolean',
        ]);

        $server->fill([
            'amavis_db_host' => $request->amavis_db_host,
            'amavis_db_name' => $request->amavis_db_name,
            'amavis_db_user' => $request->amavis_db_user,
            'amavis_db_port' => $request->amavis_db_port,
            'amavis_feature_enabled' => $request->amavis_feature_enabled,
        ]);

        if ($request->exists('amavis_db_password')) {
            $server->amavis_db_password = $request->amavis_db_password;
        }

        if (! $server->amavisDatabase()->available()) {
            throw ValidationException::withMessages(['amavis_db_host' => 'Datenbank ist nicht verfügbar.']);
        }

        $server->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Amavis Feature erfolgreich aktualisiert.',
            'data' => $server
        ]);
    }

    public function show(Server $server)
    {
        return response()->json([
            'status' => 'success',
            'message' => null,
            'data' => $server->only(['amavis_db_host', 'amavis_db_name', 'amavis_db_user', 'amavis_db_port', 'amavis_feature_enabled']),
        ]);
    }
}
