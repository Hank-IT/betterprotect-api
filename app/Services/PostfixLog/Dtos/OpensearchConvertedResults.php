<?php

namespace App\Services\PostfixLog\Dtos;

class OpensearchConvertedResults
{
    public function __construct(protected array $convertedResults, protected int $hits, protected string $relation) {}

    public function getConvertedResults(): array
    {
        return $this->convertedResults;
    }

    public function getHits(): int
    {
        return $this->hits;
    }

    public function getRelation(): string
    {
        return $this->relation;
    }
}
