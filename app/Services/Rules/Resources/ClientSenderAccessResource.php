<?php

namespace App\Services\Rules\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientSenderAccessResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->getKey(),
            'action' => $this->action,
            'active' => $this->active,
            'client_type' => $this->client_type,
            'client_payload' => $this->client_payload,
            'sender_type' => $this->sender_type,
            'sender_payload' => $this->sender_payload,
            'priority' => $this->priority,
            'message' => $this->message,
            'description' => $this->description,
            'created_at' => $this->created_at,
        ];
    }
}
