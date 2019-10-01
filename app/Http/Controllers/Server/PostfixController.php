<?php

namespace App\Http\Controllers\Server;

use App\Models\Server;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class PostfixController extends Controller
{
    public function store(Request $request, Server $server)
    {
        $this->validate($request, [
            'postfix_db_host' => 'required|string',
            'postfix_db_name' => 'required|string',
            'postfix_db_user' => 'nullable|string',
            'postfix_db_password' => 'nullable|string',
            'postfix_db_port' => 'required|int',
        ]);

        $server->fill([
            'postfix_db_host' => $request->postfix_db_host,
            'postfix_db_name' => $request->postfix_db_name,
            'postfix_db_user' => $request->postfix_db_user,
            'postfix_db_password' => $request->postfix_db_password,
            'postfix_db_port' => $request->postfix_db_port,
            'postfix_feature_enabled_' => true,
        ]);

        if (! $server->postfixDatabase()->available()) {
            throw ValidationException::withMessages(['postfix_db_host' => 'Datenbank ist nicht verfügbar.']);
        }

        $server->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Postfix Feature erfolgreich aktiviert.',
            'data' => $server
        ], Response::HTTP_OK);
    }
}
