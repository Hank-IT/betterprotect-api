<?php

namespace App\Services\PostfixQueue\Dtos;

class PostfixQueueEntryRecipient
{
    public function __construct(
        protected string $address,
        protected ?string $delayReason,
    ) {}

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getDelayReason(): ?string
    {
        return $this->delayReason;
    }
}
