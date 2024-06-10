<?php

namespace App\Services\Order\Actions;

use App\Services\Order\Contracts\Orderable;

class FixItemOrder
{
    public function execute(Orderable $orderable): void
    {
        $priority = 0;

        $orderable
            ->query()
            ->orderBy($orderable->getOrderColumn())->get()->each(function (Orderable $model) use (&$priority) {
                $model->updateOrder($priority);

                $priority++;
            });
    }
}
