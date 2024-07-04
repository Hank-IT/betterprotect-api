<?php

namespace App\Services\BetterprotectPolicy\Dtos;

use App\Services\BetterprotectPolicy\Actions\GetMilterExceptions;
use App\Services\BetterprotectPolicy\Contracts\BetterprotectPolicyDataRetriever;
use App\Services\BetterprotectPolicy\Contracts\BetterprotectPolicyDto;

class MilterExceptionPolicyDto implements BetterprotectPolicyDto
{
    public function getDataRetriever(): BetterprotectPolicyDataRetriever
    {
        return app(GetMilterExceptions::class);
    }

    public function getTable(): string
    {
        return 'milter_exceptions';
    }

    public function getDescription(): string
    {
        return 'Milter exceptions are updating...';
    }
}
