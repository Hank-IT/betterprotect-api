<?php

namespace App\Services\PostfixQueue\Dtos;

use Carbon\Carbon;

class PostfixQueueEntry
{
    /**
     * @param $recipients PostfixQueueEntryRecipient[]
     */
    public function __construct(
        protected string $queueName,
        protected string $queueId,
        protected int $arrivalTime,
        protected int $messageSize,
        protected bool $forcedExpire,
        protected string $sender,
        protected array $recipients,
    ) {}

    public function getQueueName(): string
    {
        return $this->queueName;
    }

    public function getQueueId(): string
    {
        return $this->queueId;
    }

    public function getArrivalTime(): Carbon
    {
        return Carbon::parse($this->arrivalTime);
    }

    public function getMessageSize(): int
    {
        return $this->messageSize;
    }

    public function getForcedExpire(): bool
    {
        return $this->forcedExpire;
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    /**
     * @return PostfixQueueEntryRecipient[]
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }
}
