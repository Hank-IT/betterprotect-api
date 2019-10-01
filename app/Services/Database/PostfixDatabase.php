<?php

namespace App\Services\Database;

class PostfixDatabase extends Database
{
    protected function config()
    {
        return [
            'driver' => 'mysql',
            'host' => $this->server->postfix_db_host,
            'port' => $this->server->postfix_db_port,
            'database' => $this->server->postfix_db_name,
            'username' => $this->server->postfix_db_user,
            'password' => $this->server->postfix_db_password,
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
        return str_replace('.', '_', $this->server->hostname) . '_postfix';
    }

    protected function getMigrationPath()
    {
        return 'database' . DIRECTORY_SEPARATOR . 'postfix-migrations';
    }
}
