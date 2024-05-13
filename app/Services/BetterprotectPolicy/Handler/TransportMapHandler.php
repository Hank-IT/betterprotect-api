<?php

namespace App\Services\BetterprotectPolicy\Handler;

use App\Services\BetterprotectPolicy\Actions\GetTransportMaps;
use App\Services\Tasks\Events\TaskProgress;

class TransportMapHandler extends AbstractHandler
{
    public function install(string $uniqueTaskId): void
    {
        TaskProgress::dispatch($uniqueTaskId, 'Transport entries are updating...');

        $this->insert(app(GetTransportMaps::class)->execute());
    }

    protected function table(): string
    {
        return 'transport_maps';
    }
}
