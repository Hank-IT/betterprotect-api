<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\BetterprotectPolicy\Jobs\BetterprotectPolicyInstallation;
use App\Services\Server\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BetterprotectPolicyController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'server_id' => 'required|integer|exists:servers,id'
        ]);

        BetterprotectPolicyInstallation::dispatch(Server::findOrFail($request->server_id), Auth::user(), 'postfix_db')
            ->onQueue('task');

        return response()->json([
            'status' => 'success',
            'message' => 'Aufgabe wurde eingereiht.',
            'data' => [],
        ], Response::HTTP_ACCEPTED);
    }
}
