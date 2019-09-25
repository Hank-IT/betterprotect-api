<?php

namespace App\Jobs;

use App\Models\Task;
use App\Models\Server;
use Carbon\Carbon;
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
        $task = Task::create([
            'message' => 'Datenbank auf Server ' . $this->server->hostname . ' wird aktualisiert...',
            'task' => 'migrate-server-db',
            'username' => $this->user->username,
        ]);

        $serverDatabase = app(ServerDatabaseService::class, [
            'server' => $this->server
        ]);

        if ($serverDatabase->migrate() == 0) {
            $task->update(['message' => 'Datenbank erfolgreich aktualisiert.', 'status' => Task::STATUS_FINISHED, 'endDate' => Carbon::now()]);
        } else {
            $task->update(['message' => 'Datenbank konnte nicht aktualisiert werden.', 'status' => Task::STATUS_ERROR, 'endDate' => Carbon::now()]);
        }
    }
}
