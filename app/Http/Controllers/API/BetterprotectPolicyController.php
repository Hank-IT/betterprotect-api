<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\BetterprotectPolicy\Jobs\BetterprotectPolicyInstallation;
use App\Services\Server\Models\Server;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class BetterprotectPolicyController extends Controller
{
    public function store(Server $server)
    {
        BetterprotectPolicyInstallation::dispatch(
            $server, (string) Str::uuid(), Auth::user()->username,
        )->onQueue('task');

        return response(status: Response::HTTP_ACCEPTED);
    }
}
