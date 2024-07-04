<?php

namespace App\Services\Server\Commands;

use Exception;
use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Database\Migrations\Migrator;

/**
 * Class MigrateCheckCommand
 * @package App\Console\Commands
 *
 * @url https://github.com/erjanmx/laravel-migrate-check/blob/master/src/Commands/MigrateCheckCommand.php
 */
class MigrateCheckCommand extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:check {--database= : The database connection to use.}
                {--path= : The path of migrations files to be executed.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows pending migrations. Command exits with non zero code if there are migrations to run';

    /**
     * The migrator instance.
     *
     * @var \Illuminate\Database\Migrations\Migrator
     */
    protected $migrator;

    /**
     * Create a command instance.
     *
     * @param Migrator $migrator
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->migrator = app('migrator');
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $files = $this->getFiles();

        $pendingMigrations = array_diff(
            array_keys($files),
            $this->getRanMigrations()
        );

        if ($pendingMigrations) {
            $this->table(['Pending migrations'], array_map(function ($migration) {
                return [$migration];
            }, $pendingMigrations));

            return 1;
        }

        $this->info('No pending migrations.');

        return 0;
    }

    protected function getFiles(): array
    {
        return $this->migrator->usingConnection($this->option('database'), function () {
            return $this->migrator->getMigrationFiles($this->getMigrationPaths());
        });
    }

    /**
     * Gets ran migrations with repository check
     */
    public function getRanMigrations(): array
    {
        return $this->migrator->usingConnection($this->option('database'), function () {
            if (!$this->migrator->repositoryExists()) {
                return [];
            }
            return $this->migrator->getRepository()->getRan();
        });
    }
}
