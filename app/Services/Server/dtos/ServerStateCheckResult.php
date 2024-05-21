<?php

namespace App\Services\Server\dtos;

class ServerStateCheckResult
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
}
