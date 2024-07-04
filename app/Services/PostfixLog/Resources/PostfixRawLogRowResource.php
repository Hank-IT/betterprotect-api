<?php

namespace App\Services\PostfixLog\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostfixRawLogRowResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'received_at' => $this->getReceivedAt(),
            'message' => $this->getMessage(),
        ];
    }
}
