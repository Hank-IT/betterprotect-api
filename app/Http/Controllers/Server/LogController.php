<?php

namespace App\Http\Controllers\Server;

use App\Models\Server;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class LogController extends Controller
{
    public function store(Request $request, Server $server)
    {
        $this->validate($request, [
            'log_db_host' => 'required|string',
            'log_db_name' => 'required|string',
            'log_db_user' => 'nullable|string',
            'log_db_password' => 'nullable|string',
            'log_db_port' => 'required|int',
        ]);

        $server->fill([
            'log_db_host' => $request->log_db_host,
            'log_db_name' => $request->log_db_name,
            'log_db_user' => $request->log_db_user,
            'log_db_password' => $request->log_db_password,
            'log_db_port' => $request->log_db_port,
            'log_feature_enabled' => true,
        ]);

        if (! $server->logDatabase()->available()) {
            throw ValidationException::withMessages(['log_db_host' => 'Datenbank ist nicht verfügbar.']);
        }

        $server->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Log Feature erfolgreich aktiviert.',
            'data' => $server
        ]);
    }

    public function update(Request $request, Server $server)
    {
        $this->validate($request, [
            'log_db_host' => 'required|string',
            'log_db_name' => 'required|string',
            'log_db_user' => 'nullable|string',
            'log_db_password' => 'nullable|string',
            'log_db_port' => 'required|int',
            'log_feature_enabled' => 'required|boolean',
        ]);

        $server->fill([
            'log_db_host' => $request->log_db_host,
            'log_db_name' => $request->log_db_name,
            'log_db_user' => $request->log_db_user,
            'log_db_port' => $request->log_db_port,
            'log_feature_enabled' => $request->log_feature_enabled,
        ]);

        if ($request->exists('log_db_password')) {
            $server->log_db_password = $request->log_db_password;
        }

        if (! $server->logDatabase()->available()) {
            throw ValidationException::withMessages(['log_db_host' => 'Datenbank ist nicht verfügbar.']);
        }

        $server->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Log Feature erfolgreich aktualisiert.',
            'data' => $server
        ]);
    }

    public function show(Server $server)
    {
        return response()->json([
            'status' => 'success',
            'message' => null,
            'data' => $server->only(['log_db_host', 'log_db_name', 'log_db_user', 'log_db_port', 'log_feature_enabled']),
        ]);
    }
}
