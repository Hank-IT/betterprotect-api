<?php

namespace App\Http\Controllers\Server;

use App\Models\Server;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
            'message' => 'Eintrag wurde erfolgreich hinzugefügt.',
            'data' => Server::create(['hostname' => $request->hostname, 'description' => $request->description]),
        ], Response::HTTP_CREATED);
    }
}
