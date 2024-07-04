<?php

namespace App\Services\PostfixQueue\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostfixQueueEntryResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'queue_name' => $this->getQueueName(),
            'queue_id' => $this->getQueueId(),
            'arrival_time' => $this->getArrivalTime(),
            'message_size' => $this->getMessageSize(),
            'forced_expire' => $this->getForcedExpire(),
            'sender' => $this->getSender(),
            'recipients' => PostfixQueueEntryRecipientResource::collection($this->getRecipients()),
        ];
    }
}
