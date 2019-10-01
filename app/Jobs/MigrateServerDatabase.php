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

class MigrateServerDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $server;

    protected $user;

    protected $database;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Server $server, Authenticatable $user, string $database)
    {
        $this->server = $server;

        $this->user = $user;

        $this->database = $database;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $task = Task::create([
            'message' => 'Datenbank ' . $this->database . ' auf Server ' . $this->server->hostname . ' wird aktualisiert...',
            'task' => 'migrate-server-db',
            'username' => $this->user->username,
        ]);

        $serverDatabase = app($this->database, ['server' => $this->server]);

        if ($serverDatabase->migrate() == 0) {
            $task->update(['message' => 'Datenbank ' . $this->database . ' erfolgreich aktualisiert.', 'status' => Task::STATUS_FINISHED, 'endDate' => Carbon::now()]);
        } else {
            $task->update(['message' => 'Datenbank ' . $this->database . ' konnte nicht aktualisiert werden.', 'status' => Task::STATUS_ERROR, 'endDate' => Carbon::now()]);
        }
    }
}
