<?php

namespace App\Http\Controllers\API\Policy;

use App\Http\Controllers\Controller;
use App\Services\Order\Enums\ModeEnum;
use App\Services\Order\Enums\OrderableEntitiesEnum;
use Illuminate\Validation\ValidationException;

class OrderableController extends Controller
{
    public function __invoke(OrderableEntitiesEnum $orderableEntitiesEnum, int $id, string $mode)
    {
        if (! $modeEnum = ModeEnum::tryFrom($mode)) {
            throw ValidationException::withMessages([
                'mode' => 'invalid',
            ]);
        }

        $modeEnum->getAction()->execute($orderableEntitiesEnum->find($id));

        return response(status: 200);
    }
}
