<?php

namespace App\Services\Tasks\Actions;

use App\Services\Tasks\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginateTask
{
    public function execute(int $pageNumber, int $pageSize): LengthAwarePaginator
    {
        return Task::query()->orderByDesc('startDate')->paginate($pageSize, ['*'], $pageNumber);
    }
}
