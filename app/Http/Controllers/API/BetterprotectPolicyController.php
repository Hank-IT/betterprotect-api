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
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'server_id' => ['required', 'array'],
            'server_id.*' => ['required', 'integer', Rule::exists('servers', 'id')],
        ]);

        Server::find($data['server_id'])->each(function ($server) {
            BetterprotectPolicyInstallation::dispatch(
                $server, (string) Str::uuid(), Auth::user()->username,
            )->onQueue('task');
        });

        return response(status: Response::HTTP_ACCEPTED);
    }
}
