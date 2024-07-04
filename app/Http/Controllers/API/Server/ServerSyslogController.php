<?php

namespace App\Http\Controllers\API\Server;

use App\Http\Controllers\Controller;
use App\Services\Server\Actions\GetPostfixSyslogFromServer;
use App\Services\Server\Models\Server;
use App\Services\Server\Resources\ServerSyslogResource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ServerSyslogController extends Controller
{
    public function __invoke(
        Request $request,
        Server $server,
        GetPostfixSyslogFromServer $getPostfixSyslogFromServer,
    ) {
        $data = $request->validate([
            'search' => ['nullable', 'string'],
            'start_date' => ['required', 'date', 'before_or_equal:end_date'],
            'end_date' => ['required', 'date'],
            'page_number' => ['required', 'integer'],
            'page_size' => ['required', 'integer'],
        ]);

        $result = $getPostfixSyslogFromServer->execute(
            $server,
            Carbon::parse($data['start_date']),
            Carbon::parse($data['end_date']),
            $data['page_number'],
            $data['page_size'],
            $data['search'] ?? null
        );

        return ServerSyslogResource::collection($result);
    }
}
