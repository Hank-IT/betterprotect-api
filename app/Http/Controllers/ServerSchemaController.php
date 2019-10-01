<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;
use App\Jobs\MigrateServerDatabase;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ServerSchemaController extends Controller
{
    public function store(Server $server, Request $request)
    {
        $this->validate($request, [
            'database' => 'required|string|in:postfix,amavis,log',
        ]);

        MigrateServerDatabase::dispatch($server, Auth::user(), $request->database)->onQueue('task');

        return response()->json([
            'status' => 'success',
            'message' => 'Aufgabe wurde eingereiht.',
            'data' => [],
        ], Response::HTTP_ACCEPTED);
    }

    public function show(Server $server, Request $request)
    {
        $this->validate($request, [
            'database' => 'required|string|in:postfix_db,amavis_db,log_db',
        ]);

        $database = app($request->database, ['server' => $server]);

        if (! $database->available()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Datenbank ist nicht verfügbar.',
                'data' => [],
            ]);
        }

        if ($database->needsMigrate()) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Datenbank ist verfügbar und muss aktualisiert werden.',
                'data' => [],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Datenbank ist verfügbar und aktuell.',
            'data' => [],
        ]);
    }
}
