<?php

namespace App\Services\PostfixLog\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostfixMailResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'program' => $this->getProgram(),
            'timestamp' => $this->getTimestamp(),
            'source' => $this->getSource(),
            'queue_id' => $this->getQueueId(),
            'message_id' => $this->getMessageId(),
            'lines' => PostfixMailLineResource::collection($this->getLines()),
            'client_hostname' => $this->getClientHostname(),
            'client_ip' => $this->getClientIp(),
            'client_ip_unknown' => $this->getClientIpUnknown(),
            'client_port' => $this->getClientPort(),
            'headers' => PostfixMailHeaderResource::collection($this->getHeaders()),
            'from' => $this->getFrom(),
            'nrcpt' => $this->getNrcpt(),
            'size' => $this->getSize(),
            'to' => $this->getTo(),
            'relay_ip' => $this->getRelayIp(),
            'relay_hostname' => $this->getRelayHostname(),
            'relay_service' => $this->getRelayService(),
            'relay_port' => $this->getRelayPort(),
            'relay_status' => $this->getRelayStatus(),
            'status' => $this->getStatus(),
            'status_message' => $this->getStatusMessage(),
            'proto' => $this->getProto(),
            'helo' => $this->getHelo(),
            'action' => $this->getAction(),
            'smtp_state' => $this->getSmtpState(),
            'status_code' => $this->getStatusCode(),
            'status_code_enhanced' => $this->getStatusCodeEnhanced(),
            'status_data' => $this->getStatusData(),
            'smtp_response' => $this->getSmtpResponse(),
            'smtp_lost_connection_data' => $this->getSmtpLostConnectionData(),
            'smtp_lost_connection_reason' => $this->getSmtpLostConnectionReason(),
            'smtp_stage' => $this->getSmtpStage(),
            'pix_workaround' => $this->getPixWorkaround(),
            'betterprotect_policy_decision' => $this->getBetterprotectPolicyDecision(),
            'opendkim_signature' => $this->getOpenDkimSignature(),
            'opendkim_domain' => $this->getOpenDkimDomain(),
            'opendkim_algorithm' => $this->getOpenDkimAlgorithm(),
            'opendkim_ssl' => $this->getOpenDkimSsl(),
            'opendkim_selector' => $this->getOpenDkimSelector(),
            'opendkim_error' => $this->getOpenDkimError(),
            'amavis_id' => $this->getAmavisId(),
            'amavis_action' => $this->getAmavisAction(),
            'amavis_category' => $this->getAmavisCategory(),
            'amavis_match' => $this->getAmavisMatch(),
            'amavis_relay_ip' => $this->getAmavisRelayIp(),
            'amavis_origin_ip' => $this->getAmavisOriginIp(),
            'amavis_from' => $this->getAmavisFrom(),
            'amavis_to' => $this->getAmavisTo(),
            'amavis_elapsedtime' => $this->getAmavisElapsedTime(),
        ];
    }
}
