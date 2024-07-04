<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Tasks\Actions\PaginateTask;
use App\Services\Tasks\Resources\TaskResource;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __invoke(Request $request, PaginateTask $paginateTask)
    {
        $data = $request->validate([
            'page_number' => ['required', 'integer'],
            'page_size' => ['required', 'integer'],
        ]);

        return TaskResource::collection($paginateTask->execute($data['page_number'], $data['page_size']));
    }
}
