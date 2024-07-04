<?php

namespace App\Services\Milter\Actions;
use App\Services\Milter\Models\Milter;

class CreateMilter
{
    public function execute(string $name, string $definition, ?string $description = null): Milter
    {
        return Milter::create([
            'name' => $name,
            'definition' => $definition,
            'description' => $description,
        ]);
    }
}
