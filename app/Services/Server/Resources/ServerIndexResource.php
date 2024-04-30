<?php

namespace App\Services\Server\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServerIndexResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->getKey(),
            'hostname' => $this->hostname,
            'last_policy_install' => $this->last_policy_install,
        ];
    }
}
