<?php

namespace App\Services\BetterprotectPolicy\Handler;

use App\Services\BetterprotectPolicy\Actions\GetClientSenderAccess;
use App\Services\Tasks\Events\TaskProgress;

class ClientSenderAccessHandler extends AbstractHandler
{
    public function install(string $uniqueTaskId): void
    {
        TaskProgress::dispatch($uniqueTaskId, 'Rules are updating...');

        $this->insert(app(GetClientSenderAccess::class)->execute());
    }

    protected function table(): string
    {
        return 'client_sender_access';
    }
}
