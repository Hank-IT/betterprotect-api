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

       /* return [
            'queue_id' => $this['queue_id'] ?? null,
            'client' => $this['client'] ?? null,
            'client_ip' => $this['client_ip'] ?? null,
            'host' => $this['host'] ?? null,
            'reported_at' => $this['reported_at'] ?? null,
            'enc_type' => $this['enc_type'] ?? null,
            'enc_direction' => $this['enc_direction'] ?? null,
            'enc_ip' => $this['enc_ip'] ?? null,
            'enc_cipher' => $this['enc_cipher'] ?? null,
            'process' => $this['process'] ?? null,
            'subject' => $this['subject'] ?? null,
            'from' => $this['from'] ?? null,
            'size' => $this['size'] ?? null,
            'nrcpt' => $this['nrcpt'] ?? null,
            'to' => $this['to'] ?? null,
            'relay' => $this['relay'] ?? null,
            'relay_ip' => $this['relay_ip'] ?? null,
            'delay' => $this['delay'] ?? null,
            'delays' => $this['delays'] ?? null,
            'dsn' => $this['dsn'] ?? null,
            'status' => $this['status'] ?? null,
            'response' => $this['response'] ?? null,
        ];*/
    }
}
