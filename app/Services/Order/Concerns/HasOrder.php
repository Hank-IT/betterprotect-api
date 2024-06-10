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

    public function incrementOrder(): void
    {
        $this->increment($this->getOrderColumn());
    }

    public function decrementOrder(): void
    {
        $this->decrement($this->getOrderColumn());
    }

    public function updateOrder(int $value): void
    {


        $rfesult = $this->update([
            $this->getOrderColumn() => $value,
        ]);

      //  dump($rfesult);
     //   dump($value);
    }
}
