<?php

namespace App\Services\PostfixLog\Dtos;

class PostfixRecipient
{
    public function __construct(protected array $data) {}

    public function getTo(): ?string
    {
        return $this->data['postfix_to'] ?? null;
    }

    public function getRelayIp(): ?string
    {
        return $this->data['postfix_relay_ip'] ?? null;
    }

    public function getRelayStatus(): ?string
    {
        return $this->data['postfix_relay_status'] ?? null;
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

    public function getDelayInQmgr(): ?string
    {
        return $this->data['postfix_delay_in_qmgr'] ?? null;
    }

    public function getDelayBeforeQmgr():? string
    {
        return $this->data['postfix_delay_before_qmgr'] ?? null;
    }

    public function getDelayConnSetup(): ?string
    {
        return $this->data['postfix_delay_conn_setup'] ?? null;
    }

    public function getDelayTransmission(): ?string
    {
        return $this->data['postfix_delay_transmission'] ?? null;
    }

    public function getDelay(): ?string
    {
        return $this->data['postfix_delay'] ?? null;
    }

    public function getStatus(): ?string
    {
        return $this->data['postfix_status'] ?? null;
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

    public function getDsn(): ?string
    {
        return $this->data['postfix_dsn'] ?? null;
    }
}
