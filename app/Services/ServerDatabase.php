<?php

namespace App\Services;

use App\Models\Server;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class ServerDatabase
{
    protected $server;

    protected $output;

    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    public function migrate()
    {
        return tap(Artisan::call('migrate', [
            '--database' => $this->getPolicyConnectionString(),
            '--no-interaction' => 'true',
            '--path' => $this->getMigrationPath(),
            '--force' => 'true']),
            function() {
                $this->output = Artisan::output();

                Log::debug($this->output);

                // Reset the config. Apparently the artisan command
                // updates the default database connection.
                Config::set('database.default', 'mysql');
        });
    }

    /**
     * @return bool
     */
    public function needsMigrate()
    {
        return tap(Artisan::call('migrate:check', [
            '--database' => $this->getPolicyConnectionString(),
                '--no-interaction' => 'true',
                '--path' => $this->getMigrationPath()]),
                function() {
                    // Reset the config. Apparently the artisan command
                    // updates the default database connection.
                    Config::set('database.default', 'mysql');
        }) !== 0;
    }

    public function available()
    {
        try {
            $this->getPolicyConnection()->getPdo();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function getPolicyConnection(): ConnectionInterface
    {
        return DB::connection($this->getPolicyConnectionString());
    }

    public function getLogConnection()
    {
        $name = str_replace('.', '_log_', $this->server->hostname);

        Config::set('database.connections.'. $name, [
            'driver' => 'mysql',
            'host' => $this->server->db_host,
            'port' => $this->server->db_port,
            'database' => 'Syslog',
            'username' => $this->server->db_name,
            'password' => $this->server->db_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ]);

        return DB::connection($name);
    }

    protected function getPolicyConnectionString()
    {
        $name = str_replace('.', '_', $this->server->hostname);

        Config::set('database.connections.'. $name, [
            'driver' => 'mysql',
            'host' => $this->server->db_host,
            'port' => $this->server->db_port,
            'database' => $this->server->db_name,
            'username' => $this->server->db_name,
            'password' => $this->server->db_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ]);

        return $name;
    }

    protected function getMigrationPath()
    {
        return 'database' . DIRECTORY_SEPARATOR . 'managed-server-migrations';
    }
}