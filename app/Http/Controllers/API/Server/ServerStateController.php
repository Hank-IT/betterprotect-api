<?php

namespace App\Http\Controllers\API\Server;

use App\Http\Controllers\Controller;
use App\Services\Server\Actions\GetServerStateFromCache;
use App\Services\Server\Models\Server;
use App\Services\Server\Resources\ServerStateResource;

class ServerStateController extends Controller
{
    public function __invoke(Server $server, GetServerStateFromCache $getServerStateFromCache)
    {
        return new ServerStateResource(
            $getServerStateFromCache->execute($server->hostname)
        );
    }
}
