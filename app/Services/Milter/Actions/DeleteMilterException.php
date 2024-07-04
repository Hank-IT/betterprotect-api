<?php

namespace App\Services\Milter\Actions;

use App\Services\Milter\Models\MilterException;

class DeleteMilterException
{
    public function execute(MilterException $milterException): ?bool
    {
        return $milterException->delete();
    }
}
