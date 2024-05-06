<?php

namespace App\Services\RelayDomains\Actions;

use App\Services\RelayDomains\Models\RelayDomain;

class DeleteRelayDomain
{
    public function execute(RelayDomain $relayDomain): ?bool
    {
        return $relayDomain->delete();
    }
}
