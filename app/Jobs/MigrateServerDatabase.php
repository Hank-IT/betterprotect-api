<?php

namespace App\Jobs;

use App\Models\Task;
use App\Services\Server\Models\Server;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

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
        Config::set('database.default', 'mysql');

        $task = Task::create([
            'message' => 'Datenbank ' . $this->database . ' auf Server ' . $this->server->hostname . ' wird aktualisiert...',
            'task' => 'migrate-server-db',
            'username' => $this->user->username,
        ]);

        $serverDatabase = app($this->database, ['server' => $this->server]);

        $serverDatabase->migrate() == 0
            ? $task->update(['message' => 'Datenbank ' . $this->database . ' erfolgreich aktualisiert.', 'status' => Task::STATUS_FINISHED, 'endDate' => Carbon::now()])
            : $task->update(['message' => 'Datenbank ' . $this->database . ' konnte nicht aktualisiert werden.', 'status' => Task::STATUS_ERROR, 'endDate' => Carbon::now()]);
    }
}
