<?php

namespace App\Services\BetterprotectPolicy\Actions;

use App\Services\Server\Database;
use App\Services\Server\Database\PostfixDatabase;
use App\Services\Server\Models\Server;

class InstallPolicy
{
    public function execute(Server $server, array $handlers, string $uniqueTaskId): void
    {
        $serverDatabase = new Database('postfix', $server->postfixDatabaseDetails());

        foreach ($handlers as $handler) {
            app($handler, ['dbConnection' => $serverDatabase->getConnection()])->install($uniqueTaskId);
        }
    }
}
