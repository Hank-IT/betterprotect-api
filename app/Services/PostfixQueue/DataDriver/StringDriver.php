<?php

namespace App\Services\PostfixQueue\DataDriver;

use App\Services\PostfixQueue\Contracts\DataDriver as DataDriverContract;

class StringDriver implements DataDriverContract
{
    public function __construct(protected string $data) {}

    public function get(): string
    {
        return $this->data;
    }
}
