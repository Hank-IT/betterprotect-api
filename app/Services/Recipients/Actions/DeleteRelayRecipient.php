<?php

namespace App\Services\Recipients\Actions;

use App\Services\Recipients\Models\RelayRecipient;

class DeleteRelayRecipient
{
    public function execute(RelayRecipient $relayRecipient): ?bool
    {
        return $relayRecipient->delete();
    }
}
