<?php

namespace App\Services\PostfixLog\Dtos;

use Carbon\Carbon;

class PostfixMailLine
{
    public function __construct(protected array $line) {}

    public function getMessage(): ?string
    {
        return $this->line['message'] ?? null;
    }

    public function getProgram(): ?string
    {
        return $this->line['program'] ?? null;
    }

    public function getTimestamp(): ?Carbon
    {
        if (empty($this->line['timestamp'])) {
            return null;
        }

        return Carbon::parse($this->line['timestamp']);
    }
}
