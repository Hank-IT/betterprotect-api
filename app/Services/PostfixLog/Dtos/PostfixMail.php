<?php

namespace App\Services\PostfixLog\Dtos;

use Carbon\Carbon;

class PostfixMail
{
    public function __construct(protected array $data) {}

    public function getProgram(): string
    {
        return $this->data['program'];
    }

    public function getTimestamp(): Carbon
    {
        return Carbon::parse($this->data['timestamp8601']);
    }

    public function getSource(): string
    {
        return $this->data['logsource'];
    }

    public function getQueueId(): string
    {
        return $this->data['postfix_queueid'];
    }

    public function getMessageId(): ?string
    {
        return $this->data['postfix_message-id'] ?? null;
    }

    public function getLines(): array
    {
        if (empty($this->data['lines'])) {
            return [];
        }

        $lines = [];
        foreach($this->data['lines'] as $line) {
            $lines[] = new PostfixMailLine($line);
        }

        return $lines;
    }

    public function getClientHostname(): ?string
    {
        return $this->data['postfix_client_hostname'] ?? null;
    }

    public function getClientIp(): ?string
    {
        return $this->data['postfix_client_ip'] ?? null;
    }

    public function getClientIpUnknown(): ?string
    {
        return $this->data['postfix_client_ip_unknown'] ?? null;
    }

    public function getClientPort(): ?string
    {
        return $this->data['postfix_client_port'] ?? null;
    }

    public function getHeaders(): array
    {
        if (empty($this->data['postfix_headers'])) {
            return [];
        }

        $headers = [];
        foreach($this->data['postfix_headers'] as $header) {
            $headers[] = new PostfixMailHeader($header);
        }

       return $headers;
    }

    public function getFrom(): ?string
    {
        return $this->data['postfix_from'] ?? null;
    }

    public function getNrcpt(): ?int
    {
        return $this->data['postfix_nrcpt'] ?? null;
    }

    public function getSize(): ?int
    {
        return $this->data['postfix_size'] ?? null;
    }

    public function getTo(): ?string
    {
        return $this->data['postfix_to'] ?? null;
    }

    public function getRelayIp(): ?string
    {
        return $this->data['postfix_relay_ip'] ?? null;
    }

    public function getRelayHostname(): ?string
    {
        return $this->data['postfix_relay_hostname'] ?? null;
    }

    public function getRelayService(): ?string
    {
        return $this->data['postfix_relay_service'] ?? null;
    }

    public function getRelayPort(): ?string
    {
        return $this->data['postfix_relay_port'] ?? null;
    }

    public function getRelayStatus(): ?string
    {
        return $this->data['postfix_relay_status'] ?? null;
    }

    public function getStatus(): ?string
    {
        return $this->data['postfix_status'] ?? null;
    }

    public function getStatusMessage(): ?string
    {
        return $this->data['postfix_status_message'] ?? null;
    }

    public function getProto(): ?string
    {
        return $this->data['postfix_proto'] ?? null;
    }

    public function getHelo(): ?string
    {
        return $this->data['postfix_helo'] ?? null;
    }

    public function getAction(): ?string
    {
        return $this->data['postfix_action'] ?? null;
    }

    public function getSmtpState(): ?string
    {
        return $this->data['postfix_smtp_stage'] ?? null;
    }

    public function getStatusCode(): ?string
    {
        return $this->data['postfix_status_code'] ?? null;
    }

    public function getStatusCodeEnhanced(): ?string
    {
        return $this->data['postfix_status_code_enhanced'] ?? null;
    }

    public function getStatusData(): ?string
    {
        return $this->data['postfix_status_data'] ?? null;
    }

    public function getSmtpResponse(): ?string
    {
        return $this->data['postfix_smtp_response'] ?? null;
    }

    public function getSmtpLostConnectionData(): ?string
    {
        return $this->data['postfix_smtp_lostconn_data'] ?? null;
    }

    public function getSmtpLostConnectionReason(): ?string
    {
        return $this->data['postfix_smtp_lostconn_reason'] ?? null;
    }

    public function getSmtpStage(): ?string
    {
        return $this->data['postfix_smtp_stage'] ?? null;
    }

    public function getPixWorkaround(): ?string
    {
        return $this->data['postfix_pix_workaround'] ?? null;
    }

    public function getBetterprotectPolicyDecision(): ?string
    {
        return $this->data['betterprotect_decision'] ?? null;
    }

    public function getOpenDkimSignature(): ?string
    {
        return $this->data['opendkim_signature'] ?? null;
    }

    public function getOpenDkimDomain(): ?string
    {
        return $this->data['opendkim_domain'] ?? null;
    }

    public function getOpenDkimAlgorithm(): ?string
    {
        return $this->data['opendkim_algorithm'] ?? null;
    }

    public function getOpenDkimSsl(): ?string
    {
        return $this->data['opendkim_ssl'] ?? null;
    }

    public function getOpenDkimSelector(): ?string
    {
        return $this->data['opendkim_selector'] ?? null;
    }

    public function getOpenDkimError(): ?string
    {
        return $this->data['opendkim_error'] ?? null;
    }

    public function getAmavisId(): ?string
    {
        return $this->data['amavis_id'] ?? null;
    }

    public function getAmavisAction(): ?string
    {
        return $this->data['amavis_action'] ?? null;
    }

    public function getAmavisCategory(): ?string
    {
        return $this->data['amavis_category'] ?? null;
    }

    public function getAmavisMatch(): ?string
    {
        return $this->data['amavis_match'] ?? null;
    }

    public function getAmavisRelayIp(): ?string
    {
        return $this->data['amavis_relay_ip'] ?? null;
    }

    public function getAmavisOriginIp(): ?string
    {
        return $this->data['amavis_amavis_origin_ip'] ?? null;
    }

    public function getAmavisFrom(): ?string
    {
        return $this->data['amavis_from'] ?? null;
    }

    public function getAmavisTo(): ?string
    {
        return $this->data['amavis_to'] ?? null;
    }

    public function getAmavisElapsedTime(): ?string
    {
        return $this->data['amavis_elapsedtime'] ?? null;
    }
}
