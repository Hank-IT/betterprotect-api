<?php

namespace App\Services\BetterprotectPolicy\Handler;

use App\Services\BetterprotectPolicy\Actions\GetRelayRecipients;
use App\Services\Tasks\Events\TaskProgress;

class RecipientAccessHandler extends AbstractHandler
{
    public function install(string $uniqueTaskId): void
    {
        TaskProgress::dispatch($uniqueTaskId, 'Recipients are updating...');

        $this->insert(app(GetRelayRecipients::class)->execute());
    }

    protected function table(): string
    {
        return 'relay_recipients';
    }
}
