<?php

namespace App\Services\Server\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServerStateResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return collect($this->resource->toArray())->map(function($item, $key) {
            if (! $item) {
                return [
                    'key' => $key,
                ];
            }

            return array_merge($item->toArray(), [
                'key' => $key,
            ]);
        })->values();
    }
}
