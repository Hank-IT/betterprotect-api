<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Jobs\MigrateServerDatabase;
use Illuminate\Support\Facades\Auth;
use App\Services\ServerDatabase as ServerDatabaseService;
use Symfony\Component\HttpFoundation\Response;

class ServerSchemaController extends Controller
{
    /**
     * Create the server's database.
     *
     * @param Server $server
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Server $server)
    {
        MigrateServerDatabase::dispatch($server, Auth::user())->onQueue('task');

        return response()->json([
            'status' => 'success',
            'message' => 'Aufgabe wurde eingereiht.',
            'data' => [],
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * Check the server's database.
     *
     * @param ServerDatabaseService $database
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Server $server)
    {
        $database = app(ServerDatabaseService::class, ['server' => $server]);

        if ($database->available()) {
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

        return response()->json([
            'status' => 'error',
            'message' => 'Datenbank ist nicht verfügbar.',
            'data' => [],
        ]);
    }
}
