<?php

namespace App\Services\Server\dtos;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class ServerStateCheckResult implements Arrayable
{
    public function __construct(protected bool $available, protected Carbon $timestamp, protected ?string $description = null) {}

    public function getAvailable(): bool
    {
        return $this->available;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getTimestamp(): Carbon
    {
        return $this->timestamp;
    }

    public function toArray(): array
    {
        return [
            'available' => $this->getAvailable(),
            'description' => $this->getDescription(),
            'timestamp' => $this->getTimestamp(),
        ];
    }
}
