<?php

namespace App\Services\Activation\Concerns;
use Illuminate\Database\Eloquent\Builder;

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

    public function scopeActive(Builder $query): void
    {
        $query->where('active', '=', true);
    }
}
