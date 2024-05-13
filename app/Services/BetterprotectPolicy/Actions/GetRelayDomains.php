<?php

namespace App\Services\BetterprotectPolicy\Actions;

use App\Services\BetterprotectPolicy\Repositories\RelayDomainRepository;

class GetRelayDomains
{
    public function __construct(protected RelayDomainRepository $relayDomainRepository) {}

    public function execute(): array
    {
        $this->relayDomainRepository->get()->toArray();
    }
}
