<?php

namespace App\Services\BetterprotectPolicy\Handler;

use App\Services\BetterprotectPolicy\Actions\GetMilterExceptions;
use App\Services\Tasks\Events\TaskProgress;

class MilterExceptionHandler extends AbstractHandler
{
    public function install(string $uniqueTaskId): void
    {
        TaskProgress::dispatch($uniqueTaskId, 'Milter exceptions are updating...');

        $this->insert(app(GetMilterExceptions::class)->execute());
    }

    public function table(): string
    {
        return 'milter_exceptions';
    }
}
