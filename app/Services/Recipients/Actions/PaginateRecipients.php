<?php

namespace App\Services\Recipients\Actions;

use App\Services\Recipients\Models\RelayRecipient;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginateRecipients
{
    public function execute(int $pageNumber, int $pageSize, ?string $search = null): LengthAwarePaginator
    {
        $query = RelayRecipient::query();

        if ($search) {
            $query->where('payload', 'LIKE', '%' . $search . '%');
        }

        return $query->paginate($pageSize, ['*'], '', $pageNumber);
    }
}
