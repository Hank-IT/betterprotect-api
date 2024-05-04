<?php

namespace App\Services\Milter\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MilterExceptionResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'client_type' => $this->client_type,
            'client_payload' => $this->client_payload,
            'description' => $this->description,
            'priority' => $this->priority,
            'active' => $this->active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'milters' => MilterResource::collection($this->milters),
        ];
    }
}
