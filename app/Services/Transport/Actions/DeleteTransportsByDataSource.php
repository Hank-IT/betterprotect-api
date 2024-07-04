<?php

namespace App\Services\Transport\Actions;

use App\Services\Transport\Models\Transport;

class DeleteTransportsByDataSource
{
    public function execute(string $dataSource): void
    {
        Transport::query()->where('data_source', '=', $dataSource)->delete();
    }
}
