<?php

namespace App\Services\PostfixLog\Dtos;

class LogSearchDto
{
    public function __construct(protected string $search, protected array $fields) {}

    public function getSearch(): string
    {
        return $this->search;
    }

    public function getFields(): array
    {
        return $this->fields;
    }
}
