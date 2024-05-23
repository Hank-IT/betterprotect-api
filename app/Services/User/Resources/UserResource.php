<?php

namespace App\Services\User\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->getKey(),
            'username' => $this->username,
            'role' => $this->role,
            'email' => $this->email,
        ];
    }
}
