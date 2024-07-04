<?php

namespace App\Services\PostfixLog\Dtos;

class PostfixMailHeader
{
    public function __construct(protected array $header) {}

    public function getName(): ?string
    {
        return $this->header['name'] ?? null;
    }

    public function getValue(): ?string
    {
        return $this->header['value'] ?? null;
    }
}
