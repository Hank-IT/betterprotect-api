<?php

namespace App\Services\BetterprotectPolicy\Actions;

use App\Services\BetterprotectPolicy\Repositories\RelayRecipientRepository;

class GetRelayRecipients
{
    public function __construct(protected RelayRecipientRepository $relayRecipientRepository) {}

    public function execute(): array
    {
        return $this->relayRecipientRepository->get()->map(function ($row) {
            return collect($row->toArray())
                ->only(['payload', 'action'])
                ->all();
        })->toArray();
    }
}
