<?php

namespace App\Services\PostfixLog\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostfixRawLogRowCollection extends ResourceCollection
{
    public function __construct($resource, protected int $total, protected string $relation)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'total' => $this->total,
                'relation' => $this->relation,
            ],
        ];
    }
}
