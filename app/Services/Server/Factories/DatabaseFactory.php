<?php

namespace App\Services\Server\Factories;

use App\Services\Server\Database;
use App\Services\Server\dtos\DatabaseDetails;

class DatabaseFactory
{
    public function make(string $name, DatabaseDetails $databaseDetails): Database
    {
        return new Database($name, $databaseDetails);
    }
}
