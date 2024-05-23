<?php

namespace App\Services\Server\dtos;

use Illuminate\Contracts\Support\Arrayable;

class ServerStateCheckResult implements Arrayable
{
    public function __construct(protected bool $available, protected ?string $description = null) {}

    public function getAvailable(): bool
    {
        return $this->available;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function toArray()
    {
        return [
            'available' => $this->getAvailable(),
            'description' => $this->getDescription(),
        ];
    }
}
