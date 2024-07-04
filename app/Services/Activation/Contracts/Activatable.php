<?php

namespace App\Services\Activation\Contracts;

interface Activatable
{
    public function setActive(bool $state): void;

    public function isActive(): bool;
}
