<?php

namespace App\Services\Order\Concerns;

trait HasOrder
{
    public function getOrderColumn(): string
    {
        return 'priority';
    }

    public function getOrderColumnValue(): int
    {
        return $this->priority;
    }
}
