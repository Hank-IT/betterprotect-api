<?php

namespace App\Services\BetterprotectPolicy\Jobs;

use App\Services\BetterprotectPolicy\Actions\InstallPolicy;
use App\Services\Server\Models\Server;
use App\Services\Tasks\Events\TaskCreated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BetterprotectPolicyInstallation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public $timeout = 300;

    public function __construct(
        protected Server $server,
        protected string $uniqueTaskId,
        string $dispatchingUserName,
    ) {
        TaskCreated::dispatch($this->uniqueTaskId, 'install-policy', $dispatchingUserName);
    }

    public function handle(InstallPolicy $installPolicy): void
    {
        if ($this->attempts() >= 1) {
            $this->delete();
        }

        $installPolicy->execute($this->server, $this->uniqueTaskId);
    }
}
