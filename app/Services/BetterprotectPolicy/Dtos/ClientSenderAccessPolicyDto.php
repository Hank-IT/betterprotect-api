<?php

namespace App\Services\BetterprotectPolicy\Dtos;

use App\Services\BetterprotectPolicy\Actions\GetClientSenderAccess;
use App\Services\BetterprotectPolicy\Contracts\BetterprotectPolicyDataRetriever;
use App\Services\BetterprotectPolicy\Contracts\BetterprotectPolicyDto;

class ClientSenderAccessPolicyDto implements BetterprotectPolicyDto
{
    public function getDataRetriever(): BetterprotectPolicyDataRetriever
    {
        return app(GetClientSenderAccess::class);
    }

    public function getTable(): string
    {
        return 'client_sender_access';
    }

    public function getDescription(): string
    {
        return 'Rules are updating...';
    }
}
