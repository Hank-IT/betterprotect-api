<?php

namespace App\Services\Recipients\Actions;

use App\Services\Recipients\Models\RelayRecipient;

class PruneObsoleteRecipientsForDataSource
{
    public function execute(array $recipients, string $datasource): void
    {
        RelayRecipient::query()
            ->where('data_source', '=', $datasource)
            ->get()
            ->each(function($address) use($recipients) {
                if (! in_array($address->payload, $recipients)) {
                    // Address is not inside in data source anymore
                    $address->delete();
                }
            });

    }
}
