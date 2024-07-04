<?php

namespace App\Services\Tasks\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskProgressResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->getKey(),
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
