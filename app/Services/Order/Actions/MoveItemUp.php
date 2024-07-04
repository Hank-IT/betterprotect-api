<?php

namespace App\Services\Order\Actions;

use App\Services\Order\Contracts\Orderable;

class MoveItemUp
{
    public function __construct(protected FixItemOrder $fixItemOrder) {}

    public function execute(Orderable $orderable): void
    {
        $this->fixItemOrder->execute($orderable);

        $orderable->refresh();

        $orderable->query()
            ->where($orderable->getOrderColumn(), '=', $orderable->getOrderColumnValue() - 1)
            ->increment($orderable->getOrderColumn());

        $orderable->decrementOrder();

        $this->fixItemOrder->execute($orderable);
    }
}
