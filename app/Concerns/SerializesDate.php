<?php

namespace App\Concerns;

use DateTimeInterface;

trait SerializesDate
{
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
