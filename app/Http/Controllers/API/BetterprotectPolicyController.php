<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\BetterprotectPolicy\Jobs\BetterprotectPolicyInstallation;
use App\Services\Server\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class BetterprotectPolicyController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'server_id' => ['required', 'integer', Rule::exists('servers', 'id')],
        ]);

        BetterprotectPolicyInstallation::dispatch(
            Server::findOrFail($data['server_id']), (string) Str::uuid(), Auth::user()->username,
        )->onQueue('task');

        return response(status: Response::HTTP_ACCEPTED);
    }
}
