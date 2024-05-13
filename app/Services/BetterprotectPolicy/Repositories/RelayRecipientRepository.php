<?php

namespace App\Services\BetterprotectPolicy\Repositories;

use App\Services\Recipients\Models\RelayRecipient;
use Illuminate\Database\Eloquent\Collection;

class RelayRecipientRepository
{
    public function get(): Collection
    {
        return RelayRecipient::query()->active()->get();
    }
}
