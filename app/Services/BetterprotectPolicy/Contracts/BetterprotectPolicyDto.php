<?php

namespace App\Services\BetterprotectPolicy\Contracts;

interface BetterprotectPolicyDto
{
    public function getDataRetriever(): BetterprotectPolicyDataRetriever;

    public function getTable(): string;

    public function getDescription(): string;
}
