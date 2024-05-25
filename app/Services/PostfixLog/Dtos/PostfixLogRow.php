<?php

namespace App\Services\PostfixLog\Dtos;

class PostfixLogRow
{
    public function __construct(
        protected string $message,
        protected string $program,
        protected string $receivedAt,
        protected string $host,
    ) {}

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getProgram(): string
    {
        return $this->program;
    }

    public function getReceivedAt(): string
    {
        return $this->receivedAt;
    }

    public function getHost(): string
    {
        return $this->host;
    }
}
