<?php

namespace App\Services\Server;

use App\Services\Server\dtos\DatabaseDetails;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Database
{
    protected $output;

    public function __construct(protected string $name, protected DatabaseDetails $databaseDetails)
    {
        Config::set('database.connections.' . $this->name, $this->config());
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
            '--database' => $this->getConnectionString(),
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
            $this->getConnection()->getPdo();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function getConnection(): ConnectionInterface
    {
        return DB::connection($this->getConnectionString());
    }

    public function getConnectionString()
    {
        return str_replace('.', '_', $this->databaseDetails->getHostname()) . '_' . $this->name;
    }

    protected function config()
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

    protected function getMigrationPath()
    {
        return 'database' . DIRECTORY_SEPARATOR . $this->name . '-migrations';
    }
}
