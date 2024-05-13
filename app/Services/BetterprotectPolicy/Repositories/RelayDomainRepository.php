<?php

namespace App\Services\BetterprotectPolicy\Repositories;

use App\Services\RelayDomains\Models\RelayDomain;
use Illuminate\Database\Eloquent\Collection;

class RelayDomainRepository
{
    public function get(): Collection
    {
        return RelayDomain::where('active', '=', 1)->get('domain');
    }
}
