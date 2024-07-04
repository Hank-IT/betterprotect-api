<?php

namespace App\Services\BetterprotectPolicy;

use App\Services\BetterprotectPolicy\Dtos\ClientSenderAccessPolicyDto;
use App\Services\BetterprotectPolicy\Dtos\MilterExceptionPolicyDto;
use App\Services\BetterprotectPolicy\Dtos\RecipientAccessPolicyDto;
use App\Services\BetterprotectPolicy\Dtos\RelayDomainPolicyDto;
use App\Services\BetterprotectPolicy\Dtos\TransportMapPolicyDto;

class BetterprotectPolicy
{
    public function get(): array
    {
        return [
            new ClientSenderAccessPolicyDto,
            new MilterExceptionPolicyDto,
            new RecipientAccessPolicyDto,
            new RelayDomainPolicyDto,
            new TransportMapPolicyDto,
        ];
    }
}
