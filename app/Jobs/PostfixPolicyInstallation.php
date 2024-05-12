<?php

namespace App\Jobs;

use App\Services\Server\Models\Server;
use App\Services\Tasks\Models\Task;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PostfixPolicyInstallation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $handler = [
        \App\Services\PostfixPolicyInstallation\ClientSenderAccessHandler::class,
        \App\Services\PostfixPolicyInstallation\RecipientAccessHandler::class,
        \App\Services\PostfixPolicyInstallation\TransportMapHandler::class,
        \App\Services\PostfixPolicyInstallation\RelayDomainHandler::class,
        \App\Services\PostfixPolicyInstallation\MilterExceptionHandler::class,
    ];

    public function __construct(protected Server $server, protected Authenticatable $user) {}

    public function handle()
    {
        if ($this->attempts() >= 1) {
            $this->delete();
        }

        $serverDatabase = app('postfix_db', ['server' => $this->server]);

        $task = Task::create([
            'message' => 'Policy wird auf Server ' . $this->server->hostname . ' installiert...',
            'task' => 'install-policy',
            'username' => $this->user->username,
        ]);

        if ($serverDatabase->needsMigrate()) {
            $task->update(['message' => 'Die Datenbank auf Server ' . $this->server->hostname . ' muss vor der Installation aktualisiert werden.', 'status' => Task::STATUS_ERROR]);

            return;
        }

        foreach ($this->handler as $handler) {
            app($handler, ['dbConnection' => $serverDatabase->getConnection(), 'task' => $task])->install();
        }

        $task->update([
            'message' => 'Policy erfolgreich auf Server ' . $this->server->hostname . ' installiert.',
            'status' => Task::STATUS_FINISHED,
            'endDate' => Carbon::now(),
        ]);

        $this->server->last_policy_install = Carbon::now();

        $this->server->save();
    }
}
