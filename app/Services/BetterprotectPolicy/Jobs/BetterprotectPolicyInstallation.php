<?php

namespace App\Services\BetterprotectPolicy\Jobs;

use App\Services\BetterprotectPolicy\Actions\InstallPolicy;
use App\Services\Server\Database;
use App\Services\Server\Database\PostfixDatabase;
use App\Services\Server\Models\Server;
use App\Services\Tasks\Events\TaskCreated;
use App\Services\Tasks\Events\TaskFailed;
use App\Services\Tasks\Events\TaskFinished;
use App\Services\Tasks\Events\TaskProgress;
use App\Services\Tasks\Events\TaskStarted;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BetterprotectPolicyInstallation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $handler = [
        \App\Services\BetterprotectPolicy\Handler\ClientSenderAccessHandler::class,
        \App\Services\BetterprotectPolicy\Handler\RecipientAccessHandler::class,
        \App\Services\BetterprotectPolicy\Handler\TransportMapHandler::class,
        \App\Services\BetterprotectPolicy\Handler\RelayDomainHandler::class,
        \App\Services\BetterprotectPolicy\Handler\MilterExceptionHandler::class,
    ];

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

        TaskStarted::dispatch($this->uniqueTaskId, Carbon::now());

        TaskProgress::dispatch($this->uniqueTaskId, sprintf('Policy is installing on server %s', $this->server->hostname));

        $serverDatabase = new Database('postfix', $this->server->postfixDatabaseDetails());

        if ($serverDatabase->needsMigrate()) {
            TaskFailed::dispatch(
                $this->uniqueTaskId,
                sprintf('Database on server %s needs migration before policy installation.', $this->server->hostname),
                Carbon::now(),
            );

            return;
        }

        $installPolicy->execute($this->server, $this->handler, $this->uniqueTaskId);

        $this->server->last_policy_install = Carbon::now();

        $this->server->save();

        TaskFinished::dispatch($this->uniqueTaskId, sprintf('Policy successfully installed on %s', $this->server->hostname), Carbon::now());
    }
}
