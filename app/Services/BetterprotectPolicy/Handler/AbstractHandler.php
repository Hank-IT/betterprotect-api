<?php

namespace App\Services\BetterprotectPolicy\Handler;

use App\Services\Tasks\Models\Task;
use Illuminate\Database\ConnectionInterface;

abstract class AbstractHandler
{
    public function __construct(
        protected ConnectionInterface $dbConnection,
    ) {}

    public abstract function install(string $uniqueTaskId): void;

    protected abstract function table(): string;

    protected function insert(array $data): void
    {
        $this->dbConnection->beginTransaction();

        $this->dbConnection->getPdo()->exec('lock tables ' . $this->table() .' write');

        $this->dbConnection->table($this->table())->truncate();

        $this->dbConnection->table($this->table())->insert($data);

        $this->dbConnection->getPdo()->exec('unlock tables');

        $this->dbConnection->commit();
    }
}
