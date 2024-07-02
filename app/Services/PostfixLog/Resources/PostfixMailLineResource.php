<?php

namespace App\Services\PostfixLog\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostfixMailLineResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'timestamp' => $this->getTimestamp(),
            'program' => $this->getProgram(),
            'message' => $this->getMessage(),
        ];
    }
}
