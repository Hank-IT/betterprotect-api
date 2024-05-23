<?php

namespace App\Services\PostfixQueue\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostfixQueueEntry extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'queue_name' => $this->getQueueName(),
        ];
    }
}
