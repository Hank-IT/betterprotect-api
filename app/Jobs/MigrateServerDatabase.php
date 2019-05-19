<?php

namespace App\Jobs;

use App\Models\Server;
use App\Services\ViewTask;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Services\ServerDatabase as ServerDatabaseService;

class MigrateServerDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $server;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Server $server, Authenticatable $user)
    {
        $this->server = $server;

        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $viewTask = app(ViewTask::class, [
            'message' => 'Datenbank auf Server ' . $this->server->hostname . ' wird aktualisiert...',
            'task' => 'migrate-server-db',
            'username' => $this->user->username,
        ]);

        $serverDatabase = app(ServerDatabaseService::class, [
            'server' => $this->server
        ]);

        if ($serverDatabase->migrate() == 0) {
            $viewTask->finishedSuccess('Datenbank erfolgreich aktualisiert.');
        } else {
            $viewTask->finishedError('Datenbank konnte nicht aktualisiert werden.');
        }
    }
}
