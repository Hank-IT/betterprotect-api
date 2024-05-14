<?php

namespace App\Services\BetterprotectPolicy\Dtos;

use App\Services\BetterprotectPolicy\Actions\GetRelayRecipients;
use App\Services\BetterprotectPolicy\Contracts\BetterprotectPolicyDataRetriever;
use App\Services\BetterprotectPolicy\Contracts\BetterprotectPolicyDto;

class RecipientAccessPolicyDto implements BetterprotectPolicyDto
{
    public function getDataRetriever(): BetterprotectPolicyDataRetriever
    {
        return app(GetRelayRecipients::class);
    }

    public function getTable(): string
    {
        return 'relay_recipients';
    }

    public function getDescription(): string
    {
        return 'Recipients are updating...';
    }
}
