<?php

namespace App\Services\RelayDomains\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RelayDomainResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->getKey(),
            'domain' => $this->domain,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
