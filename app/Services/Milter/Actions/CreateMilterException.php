<?php

namespace App\Services\Milter\Actions;

use App\Services\Milter\Models\MilterException;

class CreateMilterException
{
    public function execute(
        string $clientType,
        string $clientPayload,
        ?string $description = null,
    ): MilterException {
        return MilterException::create([
            'client_type' => $clientType,
            'client_payload' => $clientPayload,
            'description' => $description,
        ]);
    }
}
