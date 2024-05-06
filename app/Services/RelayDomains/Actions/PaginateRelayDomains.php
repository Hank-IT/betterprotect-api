<?php

namespace App\Services\RelayDomains\Actions;

use App\Services\Recipients\Models\RelayRecipient;
use App\Services\RelayDomains\Models\RelayDomain;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginateRelayDomains
{
    public function execute(int $pageNumber, int $pageSize, ?string $search = null): LengthAwarePaginator
    {
        $query = RelayDomain::query();

        if ($search) {
            $query->where('domain', 'LIKE', '%' . $search . '%');
        }

        return $query->paginate($pageSize, ['*'], $pageNumber);
    }
}
