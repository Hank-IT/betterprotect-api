<?php

namespace App\Services\PostfixLog\Dtos;

class PostfixRawLogRow
{
    public function __construct(protected array $data) {}

    public function getMessage(): string
    {
        return $this->data['message'];
    }

    public function getReceivedAt(): string
    {
        return $this->data['@timestamp'];
    }
}
