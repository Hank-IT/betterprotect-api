<?php

namespace App\Services\Pagination\Actions;

use Illuminate\Pagination\LengthAwarePaginator;

class PaginateArray
{
    public function execute(array $entries, int $currentPage, int $pageSize): LengthAwarePaginator
    {
        $count = count($entries);

        $offset = ($currentPage-1) * $pageSize;

        $sliced = array_slice($entries, $offset, $pageSize);

        return new LengthAwarePaginator($sliced, $count, $pageSize, $pageSize);
    }
}
