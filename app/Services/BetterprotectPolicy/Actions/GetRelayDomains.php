<?php

namespace App\Services\BetterprotectPolicy\Actions;

use App\Services\BetterprotectPolicy\Contracts\BetterprotectPolicyDataRetriever;
use App\Services\BetterprotectPolicy\Repositories\RelayDomainRepository;

class GetRelayDomains implements BetterprotectPolicyDataRetriever
{
    public function __construct(protected RelayDomainRepository $relayDomainRepository) {}

    public function execute(): array
    {
        return $this->relayDomainRepository->get()->toArray();
    }
}
