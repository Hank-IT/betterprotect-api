<?php

namespace App\Services\Server\Jobs;

use App\Services\Server\Factories\DatabaseFactory;
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
        protected string $dispatchingUserName,
        protected string $uniqueTaskId,
        protected string $database,
    ) {
        TaskCreated::dispatch($this->uniqueTaskId, 'migrate-server-db', $dispatchingUserName);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(DatabaseFactory $databaseFactory)
    {
        Config::set('database.default', 'mysql');

        TaskStarted::dispatch($this->uniqueTaskId, Carbon::now());

        TaskProgress::dispatch($this->uniqueTaskId, sprintf('Database %s on server %s is migrating...', $this->database, $this->server->hostname));

        $database = $databaseFactory->make($this->database, $this->server->getDatabaseDetails($this->database));

        $database->migrate() == 0
            ? TaskFinished::dispatch($this->uniqueTaskId, sprintf('Database %s was successfully migrated.', $this->database), Carbon::now())
            : TaskFailed::dispatch($this->uniqueTaskId, sprintf('Database %s was not migrated.', $this->database), Carbon::now());
    }
}
