<?php

namespace App\Services\Order\Actions;

use App\Services\Order\Contracts\Orderable;

class MoveItemDown
{
    public function __construct(protected FixItemOrder $fixItemOrder) {}

    public function execute(Orderable $orderable): void
    {
        $this->fixItemOrder->execute($orderable);

        $orderable->query()
            ->where($orderable->getOrderColumn(), '=', $orderable->getOrderColumnValue() + 1)
            ->decrement($orderable->getOrderColumn());

        $orderable->query()->increment($orderable->getOrderColumn());

        $this->fixItemOrder->execute($orderable);
    }
}
