<?php

namespace App\Services\MailLogging\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OpensearchMailLogResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'queue_id' => $this['_source']['postfix_queueid'] ?? null,
            'to' => $this['_source']['postfix_to'] ?? null,
            'relay' => $this['_source']['postfix_relay_hostname'] ?? null,
            'relay_ip' => $this['_source']['postfix_relay_ip'] ?? null,
            'relay_port' => $this['_source']['postfix_relay_port'] ?? null,
            'dsn' => $this['_source']['postfix_dns'] ?? null,
            'status' => $this['_source']['postfix_action'] ?? null,
            'response' => $this['_source']['postfix_smtp_response'] ?? null,
            'host' => $this['_source']['logsource'] ?? null,
            'from' => $this['_source']['postfix_from'] ?? null,
            'size' => $this['_source']['postfix_size'] ?? null,
            'nrcpt' => $this['_source']['postfix_ncrpt'] ?? null,
            'reported_at' => $this['_source']['timestamp8601'] ?? null,
            'client' => $this['_source']['postfix_client_hostname'] ?? null,
            'client_ip' => $this['_source']['postfix_client_ip'] ?? null,
            'message' => $this['_source']['message'] ?? null,
        ];
    }
}
