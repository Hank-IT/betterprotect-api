<?php

namespace Database\Factories;

use App\Services\Tasks\Enums\TaskStatusEnum;
use App\Services\Tasks\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'id' => (string) Str::uuid(),
            'username' => fake()->userName,
            'task' => 'testing',
            'started_at' => Carbon::now()->subHour(),
            'ended_at' => Carbon::now(),
            'status' => TaskStatusEnum::QUEUED->value,
        ];
    }
}
