<?php

namespace App\Services\BetterprotectPolicy\Repositories;

use App\Services\Milter\Models\MilterException;
use Illuminate\Database\Eloquent\Collection;

class MilterExceptionRepository
{
    public function get(): Collection
    {
        return MilterException::query()->active()->get();
    }
}
