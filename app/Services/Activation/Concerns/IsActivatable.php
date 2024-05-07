<?php

namespace App\Services\Activation\Concerns;
trait IsActivatable
{
    public function setActive(bool $state): void
    {
        $this->update(['active' => $state]);
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
