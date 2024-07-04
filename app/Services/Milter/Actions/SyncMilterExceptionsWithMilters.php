<?php

namespace App\Services\Milter\Actions;

use App\Services\Milter\Models\MilterException;

class SyncMilterExceptionsWithMilters
{
    public function execute(MilterException $milterException, array $milterIds): void
    {
        $milterException->milters()->sync($milterIds);
    }
}
