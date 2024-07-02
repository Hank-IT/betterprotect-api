<?php

namespace App\Services\PostfixLog\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostfixMailHeaderResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'name' => $this->getName(),
            'value' => $this->getValue(),
        ];
    }
}
