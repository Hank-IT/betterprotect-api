<?php

namespace App\Services\Recipients\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RelayRecipientResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->getKey(),
            'payload' => $this->payload,
            'data_source' => $this->data_source,
            'action' => $this->action,
            'created_at' => $this->created_at,
            'active' => $this->active,
        ];
    }
}
