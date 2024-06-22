<?php

namespace App\Services\Tasks\Actions;

use App\Services\Tasks\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginateTask
{
    public function execute(int $pageNumber, int $pageSize): LengthAwarePaginator
    {
        return Task::query()
            ->with([
                'taskProgresses' => function ($query) {
                    $query->orderByDesc('created_at');
                }
            ])
            ->orderByDesc('started_at')->paginate($pageSize, ['*'], $pageNumber);
    }
}
