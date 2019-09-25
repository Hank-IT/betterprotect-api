<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Services\ServerDatabase as ServerDatabaseService;

class PolicyInstallation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Server Server
     */
    protected $server;

    /**
     * @var Authenticatable
     */
    protected $user;

    protected $handler = [
        \App\Services\PolicyInstallation\ClientAccessHandler::class,
        \App\Services\PolicyInstallation\SenderAccessHandler::class,
        \App\Services\PolicyInstallation\RecipientAccessHandler::class,
        \App\Services\PolicyInstallation\TransportMapHandler::class,
        \App\Services\PolicyInstallation\RelayDomainHandler::class,
    ];

    public function __construct(Server $server, Authenticatable $user)
    {
        $this->server = $server;

        $this->user = $user;
    }

    public function handle()
    {
        if ($this->attempts() >= 1) {
            $this->delete();
        }

        $db = app(ServerDatabaseService::class, ['server' => $this->server]);

        $task = Task::create([
            'message' => 'Policy wird auf Server ' . $this->server->hostname . ' installiert...',
            'task' => 'install-policy',
            'username' => $this->user->username,
        ]);

        if ($db->needsMigrate()) {
            $task->update(['message' => 'Die Datenbank auf Server ' . $this->server->hostname . ' muss vor der Installation aktualisiert werden.', 'status' => Task::STATUS_ERROR]);

            return;
        }

        $dbPolicy = $db->getPolicyConnection();

        foreach ($this->handler as $handler) {
            app($handler, ['dbConnection' => $dbPolicy, 'task' => $task])->install();
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
