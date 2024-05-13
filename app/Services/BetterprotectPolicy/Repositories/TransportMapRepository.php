<?php

namespace App\Services\BetterprotectPolicy\Repositories;

use App\Services\Transport\Models\Transport;

class TransportMapRepository
{
    public function get()
    {
        return Transport::query()->active()->get();
    }
}
