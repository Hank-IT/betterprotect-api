<?php

namespace App\Services\Server\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServerSyslogResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'received_at' => $this->DeviceReportedTime,
            'message' => $this->Message,
        ];
    }
}
