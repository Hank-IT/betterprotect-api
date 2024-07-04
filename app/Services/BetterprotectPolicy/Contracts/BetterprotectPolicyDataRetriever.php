<?php

namespace App\Services\BetterprotectPolicy\Contracts;

interface BetterprotectPolicyDataRetriever
{
    public function execute(): array;
}
