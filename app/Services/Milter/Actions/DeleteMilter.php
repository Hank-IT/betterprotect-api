<?php

namespace App\Services\Milter\Actions;
use App\Services\Milter\Models\Milter;

class DeleteMilter
{
    public function execute(Milter $milter): ?bool
    {
        return $milter->delete();
    }
}
