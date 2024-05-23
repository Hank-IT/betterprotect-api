<?php

namespace App\Services\Users\Actions;

use App\Services\Authentication\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginateUsers
{
    public function execute(int $pageNumber, int $pageSize, ?string $search = null): LengthAwarePaginator
    {
        $query = User::query();

        if ($search) {
            $query->where('username', 'LIKE', '%' . $search . '%');
        }

        return $query->paginate($pageSize, ['*'], $pageNumber);
    }
}
