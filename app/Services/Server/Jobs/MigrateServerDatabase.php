<?php

namespace App\Services\Server\Jobs;

use App\Services\Server\Models\Server;
use App\Services\Tasks\Actions\CreateTask;
use App\Services\Tasks\Actions\UpdateTask;
use App\Services\Tasks\Models\Task;
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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected Server $server,
        protected Authenticatable $user,
        protected string $database,
    ) {}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CreateTask $createTask, UpdateTask $updateTask)
    {
        Config::set('database.default', 'mysql');

        $task = $createTask->execute(
            'Datenbank ' . $this->database . ' auf Server ' . $this->server->hostname . ' wird aktualisiert...',
           'migrate-server-db',
            $this->user->username,
        );

        $serverDatabase = app($this->database, ['server' => $this->server]);

        $serverDatabase->migrate() == 0
            ? $updateTask->execute($task, 'Datenbank ' . $this->database . ' erfolgreich aktualisiert.', Task::STATUS_FINISHED, Carbon::now())
            : $updateTask->execute($task, 'Datenbank ' . $this->database . ' konnte nicht aktualisiert werden.', Task::STATUS_ERROR, Carbon::now());
    }
}
