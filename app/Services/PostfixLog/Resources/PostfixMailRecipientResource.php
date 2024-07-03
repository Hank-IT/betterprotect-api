<?php

namespace App\Services\PostfixLog\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostfixMailRecipientResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'to' => $this->getTo(),
            'relay_ip' => $this->getRelayIp(),
            'relay_status' => $this->getRelayStatus(),
            'relay_hostname' => $this->getRelayHostname(),
            'relay_service' => $this->getRelayService(),
            'relay_port' => $this->getRelayPort(),
            'delay_in_qmgr' => $this->getDelayInQmgr(),
            'delay_conn_setup' => $this->getDelayConnSetup(),
            'delay_transmission' => $this->getDelayTransmission(),
            'delay' => $this->getDelay(),
            'status' => $this->getStatus(),
            'smtp_response' => $this->getSmtpResponse(),
            'smtp_lostconn_data' => $this->getSmtpLostConnectionData(),
            'smtp_lostconn_reason' => $this->getSmtpLostConnectionReason(),
            'smtp_stage' => $this->getSmtpStage(),
            'pix_workaround' => $this->getPixWorkaround(),
            'dsn' => $this->getDsn(),
        ];
    }
}
