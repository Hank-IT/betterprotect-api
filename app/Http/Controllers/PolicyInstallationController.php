<?php

namespace App\Http\Controllers;

use App\Jobs\PolicyInstallation;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PolicyInstallationController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'server_id' => 'required|integer|exists:servers,id'
        ]);

        PolicyInstallation::dispatch(Server::findOrFail($request->server_id), Auth::user())
            ->onQueue('task');

        return response()->json([
            'status' => 'success',
            'message' => 'Aufgabe wurde eingereiht.',
            'data' => [],
        ], Response::HTTP_ACCEPTED);
    }
}
