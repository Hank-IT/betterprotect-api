<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Tasks\Enums\TaskStatusEnum;
use App\Services\Tasks\Models\Task;
use Illuminate\Http\Request;

class TaskRunningController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json([
            'data' => [
                'count' => Task::query()->where('status', '=', TaskStatusEnum::RUNNING)->count(),
            ]
        ]);
    }
}
