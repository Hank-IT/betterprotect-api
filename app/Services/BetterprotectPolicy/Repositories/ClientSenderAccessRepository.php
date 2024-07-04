<?php

namespace App\Services\BetterprotectPolicy\Repositories;

use App\Services\Rules\Models\ClientSenderAccess;
use Illuminate\Database\Eloquent\Collection;

class ClientSenderAccessRepository
{
    public function get(): Collection
    {
        return ClientSenderAccess::query()->active()->get();
    }
}
