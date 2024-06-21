<?php

namespace App\Services\Server;

use Exception;
use App\Services\Server\dtos\DatabaseDetails;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Database
{
    protected string $output;

    public function __construct(protected string $name, protected DatabaseDetails $databaseDetails)
    {
        Config::set('database.connections.' . $this->getConnectionString(), $this->config());
    }

    public function migrate()
    {
        return tap(Artisan::call('migrate', [
            '--database' => $this->getConnectionString(),
            '--no-interaction' => 'true',
            '--path' => $this->getMigrationPath(),
            '--force' => 'true']),
            function() {
                $this->output = Artisan::output();

                // Reset the config. Apparently the artisan command
                // updates the default database connection.
                Config::set('database.default', 'mysql');
        });
    }

    public function needsMigrate(): bool
    {
        return tap(Artisan::call('migrate:check', [
            '--database' => $this->getConnectionString(),
                '--no-interaction' => 'true',
                '--path' => $this->getMigrationPath()]),
                function() {
                    // Reset the config. Apparently the artisan command
                    // updates the default database connection.
                    Config::set('database.default', 'mysql');
        }) !== 0;
    }

    public function available(): bool
    {
        try {
            $this->getConnection()->getPdo();
        } catch (Exception) {
            return false;
        }

        return true;
    }

    public function getOutput(): string
    {
        return $this->output;
    }

    public function getConnection(): ConnectionInterface
    {
        return DB::connection($this->getConnectionString());
    }

    public function getConnectionString(): string
    {
        return str_replace('.', '_', $this->databaseDetails->getHostname()) . '_' . $this->name;
    }

    protected function config(): array
    {
        return [
            'driver' => 'mysql',
            'host' => $this->databaseDetails->getHostname(),
            'port' => $this->databaseDetails->getPort(),
            'database' => $this->databaseDetails->getDatabase(),
            'username' => $this->databaseDetails->getUsername(),
            'password' => $this->databaseDetails->getPassword(),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ];
    }

    protected function getMigrationPath(): string
    {
        return 'database' . DIRECTORY_SEPARATOR . $this->name . '-migrations';
    }
}
