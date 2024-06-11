<?php

namespace App\Services\Transport\Actions;

use App\Services\Transport\Models\Transport;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginateTransports
{
    public function execute(int $pageNumber, int $pageSize, ?string $search = null): LengthAwarePaginator
    {
        $query = Transport::query();

        if ($search) {
            $query->where('domain', 'LIKE', '%' . $search . '%');
        }

        return $query->paginate($pageSize, ['*'], '', $pageNumber);
    }
}
