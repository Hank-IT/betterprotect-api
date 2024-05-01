<?php

namespace App\Services\Transport\Actions;

use App\Services\Transport\Models\Transport;

class DeleteManyTransports
{
    public function execute(array $ids): void
    {
        Transport::query()
            ->whereIn('id', $ids)
            ->delete();
    }
}
