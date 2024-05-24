<?php

namespace App\Services\PostfixQueue\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostfixQueueEntryRecipientResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'address' => $this->getAddress(),
            'delay_reason' => $this->getDelayReason(),
        ];
    }
}
