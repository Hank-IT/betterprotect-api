<?php

namespace App\Services\Order\Contracts;

interface Orderable
{
    public function getOrderColumn(): string;

    public function getOrderColumnValue(): int;

    public static function query();

    public function incrementOrder(): void;

    public function decrementOrder(): void;
}
