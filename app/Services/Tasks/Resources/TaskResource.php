<?php

namespace App\Services\Tasks\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->getKey(),
            'username' => $this->username,
            'task' => $this->task,
            'started_at' => $this->started_at,
            'ended_at' => $this->ended_at,
            'progress' => TaskProgressResource::collection($this->taskProgresses),
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
