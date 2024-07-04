<?php

namespace App\Services\BetterprotectPolicy\Dtos;

use App\Services\BetterprotectPolicy\Actions\GetRelayDomains;
use App\Services\BetterprotectPolicy\Contracts\BetterprotectPolicyDataRetriever;
use App\Services\BetterprotectPolicy\Contracts\BetterprotectPolicyDto;

class RelayDomainPolicyDto implements BetterprotectPolicyDto
{
    public function getDataRetriever(): BetterprotectPolicyDataRetriever
    {
        return app(GetRelayDomains::class);
    }

    public function getTable(): string
    {
        return 'relay_domains';
    }

    public function getDescription(): string
    {
        return 'Relay domains are updating...';
    }
}
