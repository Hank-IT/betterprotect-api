<?php

namespace App\Services\BetterprotectPolicy\Handler;

use App\Services\BetterprotectPolicy\Actions\GetRelayDomains;
use App\Services\Tasks\Events\TaskProgress;

class RelayDomainHandler extends AbstractHandler
{
    public function install(string $uniqueTaskId): void
    {
        TaskProgress::dispatch($uniqueTaskId, 'Relay domains are updating...');

        $this->insert(app(GetRelayDomains::class)->execute());
    }

    protected function table(): string
    {
        return 'relay_domains';
    }
}
