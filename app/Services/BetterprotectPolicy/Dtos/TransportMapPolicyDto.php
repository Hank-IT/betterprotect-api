<?php

namespace App\Services\BetterprotectPolicy\Dtos;

use App\Services\BetterprotectPolicy\Actions\GetTransportMaps;
use App\Services\BetterprotectPolicy\Contracts\BetterprotectPolicyDataRetriever;
use App\Services\BetterprotectPolicy\Contracts\BetterprotectPolicyDto;

class TransportMapPolicyDto implements BetterprotectPolicyDto
{
    public function getDataRetriever(): BetterprotectPolicyDataRetriever
    {
        return app(GetTransportMaps::class);
    }

    public function getTable(): string
    {
        return 'transport_maps';
    }

    public function getDescription(): string
    {
        return 'Transport entries are updating...';
    }
}
