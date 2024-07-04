<?php

namespace App\Services\Order\Actions;

use App\Services\Order\Contracts\Orderable;

class MoveItemDown
{
    public function __construct(protected FixItemOrder $fixItemOrder) {}

    public function execute(Orderable $orderable): void
    {
        $this->fixItemOrder->execute($orderable);

        $orderable->refresh();

        // Decrement the value of the orderable entity after the one we are currently
        // processing. If there is one it will have the same value as the $orderable.
        // The query method gives us a new query independent of the $orderable model
        $orderable->query()
            ->where($orderable->getOrderColumn(), '=', $orderable->getOrderColumnValue() + 1)
            ->decrement($orderable->getOrderColumn());

        $orderable->incrementOrder();

        $this->fixItemOrder->execute($orderable);
    }
}
