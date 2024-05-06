<?php

namespace App\Services\RelayDomains\Actions;

use App\Services\RelayDomains\Models\RelayDomain;

class CreateRelayDomain
{
    public function execute(string $domain): RelayDomain
    {
        return RelayDomain::create([
            'domain' => $domain,
        ]);
    }
}
