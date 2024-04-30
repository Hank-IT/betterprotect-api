<?php

namespace App\Services\Server\Database;

use App\Services\Server\Models\Server;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class Database
{
    protected $server;

    protected $output;

    public function __construct(Server $server)
    {
        $this->server = $server;

        Config::set('database.connections.' . $this->name(), $this->config());
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
        return DB::connection($this->name());
    }

    public function getConnectionString()
    {
        return  $this->name();
    }

    protected abstract function getMigrationPath();

    protected abstract function config();

    protected abstract function name();
}
