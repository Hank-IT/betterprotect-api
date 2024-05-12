<?php

namespace App\Services\PostfixPolicyInstallation;

use App\Services\Tasks\Models\Task;
use Illuminate\Database\ConnectionInterface;

abstract class AbstractHandler
{
    /**
     * @var ConnectionInterface
     */
    protected $dbConnection;

    /**
     * @var Task
     */
    protected $task;

    /**
     * ClientAccessHandler constructor.
     * @param $dbConnection
     */
    public function __construct(ConnectionInterface $dbConnection, Task $task)
    {
        $this->dbConnection = $dbConnection;

        $this->task = $task;
    }

    public abstract function install();

    protected abstract function table();

    protected function insert(array $data)
    {
        $this->dbConnection->beginTransaction();

        $this->dbConnection->getPdo()->exec('lock tables ' . $this->table() .' write');

        $this->dbConnection->table($this->table())->truncate();

        $this->dbConnection->table($this->table())->insert($data);

        $this->dbConnection->getPdo()->exec('unlock tables');

        $this->dbConnection->commit();
    }
}
