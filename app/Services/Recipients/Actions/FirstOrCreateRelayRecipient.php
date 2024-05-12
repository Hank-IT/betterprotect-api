<?php

namespace App\Services\Recipients\Actions;

use App\Services\Recipients\Models\RelayRecipient;

class FirstOrCreateRelayRecipient
{
    public function execute(string $payload, string $dataSource): RelayRecipient
    {
        return RelayRecipient::firstOrCreate([
            'data_source' => $dataSource,
            'payload' => $payload,
            'action' => 'OK',
        ]);
    }
}
