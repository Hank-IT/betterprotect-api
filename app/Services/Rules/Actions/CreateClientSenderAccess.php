<?php

namespace App\Services\Rules\Actions;

use App\Services\Order\Actions\FixItemOrder;
use App\Services\Order\Actions\OrderItems;
use App\Services\Rules\Models\ClientSenderAccess;

class CreateClientSenderAccess
{
    public function __construct(protected FixItemOrder $fixItemOrder) {}

    public function execute(
        string $clientType,
        string $clientPayload,
        string $senderType,
        string $senderPayload,
        string $action,
        ?string $message,
        ?string $description,
    ): ClientSenderAccess {
        $model = ClientSenderAccess::create([
            'client_type' => $clientType,
            'client_payload' => $clientPayload,
            'sender_type' => $senderType,
            'sender_payload' => $senderPayload,
            'action' => $action,
            'message' => $message,
            'description' => $description,
        ]);

        $this->fixItemOrder->execute($model);

        return $model;
    }
}
