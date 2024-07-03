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
            'client_cert_subject' => $this->getClientCertSubject(),
            'client_cert_issuer' => $this->getClientCertIssuer(),
            'client_cert_fingerprint' => $this->getClientCertFingerprint(),
            'client_cert_public_keyfingerprint' => $this->getClientCertPublicKeyFingerprint(),
            'encryption_protocol' => $this->getEncryptionProtocol(),
            'encryption_cipher' => $this->getEncryptionCipher(),
            'encryption_keysize' => $this->getEncryptionKeysize(),
            'headers' => PostfixMailHeaderResource::collection($this->getHeaders()),
            'recipients' => PostfixMailRecipientResource::collection($this->getRecipients()),
            'from' => $this->getFrom(),
            'nrcpt' => $this->getNrcpt(),
            'size' => $this->getSize(),
            'status_message' => $this->getStatusMessage(),
            'proto' => $this->getProto(),
            'helo' => $this->getHelo(),
            'action' => $this->getAction(),
            'smtp_state' => $this->getSmtpState(),
            'status_code' => $this->getStatusCode(),
            'status_code_enhanced' => $this->getStatusCodeEnhanced(),
            'status_data' => $this->getStatusData(),
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
