<?php

namespace App\Http\Controllers\API\Server;

use App\Http\Controllers\Controller;
use App\Services\Server\Factories\DatabaseFactory;
use App\Services\Server\Jobs\MigrateServerDatabase;
use App\Services\Server\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class ServerSchemaController extends Controller
{
    public function store(Server $server, Request $request)
    {
        $data = $request->validate([
            'database' => ['required', 'string', Rule::in(['postfix', 'log'])],
        ]);

        MigrateServerDatabase::dispatch(
            $server, Auth::user()->username, (string) Str::uuid(), $data['database']
        )->onQueue('task');

        return response(status: Response::HTTP_ACCEPTED);
    }

    public function show(Server $server, Request $request, DatabaseFactory $databaseFactory)
    {
        $data = $request->validate([
            'database' => ['required', 'string', Rule::in(['postfix', 'log'])],
        ]);

        $database = $databaseFactory->make($data['database'], $server->getDatabaseDetails($data['database']));

        $available = $database->available();

        return response()->json([
            'data' => [
                'database' => $data['database'],
                'available' => $available,
                'needs-migration' => $available && $database->needsMigrate(),
            ]
        ]);
    }
}
