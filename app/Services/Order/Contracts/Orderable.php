<?php

namespace App\Services\Order\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface Orderable
{
    public function getOrderColumn(): string;

    public function getOrderColumnValue(): int;

    public static function query();
}
