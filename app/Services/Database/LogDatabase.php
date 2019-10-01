<?php

namespace App\Services\Database;

class LogDatabase extends Database
{
    protected function config()
    {
        return [
            'driver' => 'mysql',
            'host' => $this->server->log_db_host,
            'port' => $this->server->log_db_port,
            'database' => $this->server->log_db_name,
            'username' => $this->server->log_db_user,
            'password' => $this->server->log_db_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ];
    }

    protected function name()
    {
        return str_replace('.', '_', $this->server->hostname) . '_log';
    }

    protected function getMigrationPath()
    {
        return 'database' . DIRECTORY_SEPARATOR . 'log-migrations';
    }
}
