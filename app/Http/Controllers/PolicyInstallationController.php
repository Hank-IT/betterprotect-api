<?php

namespace App\Http\Controllers;

use App\Jobs\PostfixPolicyInstallation;
use App\Services\Server\Models\Server;
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

        PostfixPolicyInstallation::dispatch(Server::findOrFail($request->server_id), Auth::user(), 'postfix_db')
            ->onQueue('task');

        return response()->json([
            'status' => 'success',
            'message' => 'Aufgabe wurde eingereiht.',
            'data' => [],
        ], Response::HTTP_ACCEPTED);
    }
}
