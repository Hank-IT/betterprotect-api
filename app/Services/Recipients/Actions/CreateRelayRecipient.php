<?php

namespace App\Services\Recipients\Actions;

use App\Services\Recipients\Models\RelayRecipient;

class CreateRelayRecipient
{
    public function execute(string $payload, string $dataSource)
    {
        return RelayRecipient::create([
            'data_source' => $dataSource,
            'payload' => $payload,
            'action' => 'OK',
        ]);
    }
}
