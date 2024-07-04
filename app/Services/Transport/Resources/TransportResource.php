<?php

namespace App\Services\Transport\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransportResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->getKey(),
            'domain' => $this->domain,
            'transport' => $this->transport,
            'nexthop' => $this->nexthop,
            'nexthop_port' => $this->nexthop_port,
            'nexthop_type' => $this->nexthop_type,
            'nexthop_mx' => $this->nexthop_mx,
            'created_at' => $this->created_at,
            'data_source' => $this->data_source,
            'active' => $this->active,
        ];
    }
}
