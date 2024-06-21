<?php

namespace App\Services\BetterprotectPolicy\Actions;

use App\Services\BetterprotectPolicy\BetterprotectPolicy;
use App\Services\Server\Factories\DatabaseFactory;
use App\Services\Server\Models\Server;
use App\Services\Tasks\Events\TaskFailed;
use App\Services\Tasks\Events\TaskFinished;
use App\Services\Tasks\Events\TaskProgress;
use App\Services\Tasks\Events\TaskStarted;
use Carbon\Carbon;
use Illuminate\Database\ConnectionInterface;
use Exception;

class InstallPolicy
{
    public function __construct(
        protected BetterprotectPolicy $betterprotectPolicy,
        protected DatabaseFactory     $databaseFactory,
    ) {}

    public function execute(Server $server, string $uniqueTaskId): void
    {
        TaskStarted::dispatch($uniqueTaskId, Carbon::now());

        TaskProgress::dispatch($uniqueTaskId, sprintf('Policy is installing on server %s', $server->hostname));

        $database = $this->databaseFactory->make('postfix', $server->getDatabaseDetails('postfix'));

        try {
            if (! $database->available()) {
                TaskFailed::dispatch(
                    $uniqueTaskId,
                    sprintf('Database on server %s is not available.', $server->hostname),
                    Carbon::now(),
                );

                return;
            }

            if ($database->needsMigrate()) {
                TaskFailed::dispatch(
                    $uniqueTaskId,
                    sprintf('Database on server %s needs migration before policy installation.', $server->hostname),
                    Carbon::now(),
                );

                return;
            }
        } catch(Exception $exception) {
            TaskFailed::dispatch(
                $uniqueTaskId,
                sprintf('Failed to check the database on server %s. Message: %s', $server->hostname, $exception->getMessage()),
                Carbon::now(),
            );

            return;
        }

        foreach ($this->betterprotectPolicy->get() as $dto) {
            TaskProgress::dispatch($uniqueTaskId, $dto->getDescription());

            $this->insert(
                $database->getConnection(),
                $dto->getTable(),
                $dto->getDataRetriever()->get(),
            );
        }

        $server->last_policy_install = Carbon::now();

        $server->save();

        TaskFinished::dispatch($uniqueTaskId, sprintf('Policy successfully installed on %s', $server->hostname), Carbon::now());
    }

    protected function insert(ConnectionInterface $connection, string $table, array $data): void
    {
        $connection->beginTransaction();

        $connection->getPdo()->exec(sprintf('lock tables %s write', $table));

        $connection->table($table)->truncate();

        $connection->table($table)->insert($data);

        $connection->getPdo()->exec('unlock tables');

        $connection->commit();
    }
}
