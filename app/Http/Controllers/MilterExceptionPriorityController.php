<?php

namespace App\Http\Controllers;

use App\Services\Milter\Models\MilterException;
use App\Services\Order\Actions\OrderItems;

class MilterExceptionPriorityController extends Controller
{
    public function moveUp(MilterException $exception)
    {
        app(OrderItems::class, ['model' => $exception])->moveUp();

        return response()->json([
            'status' => 'success',
            'message' => 'Eintrag wurde erfolgreich aktualisiert.',
            'data' => null,
        ]);
    }

    public function moveDown(MilterException $exception)
    {
        app(OrderItems::class, ['model' => $exception])->moveDown();

        return response()->json([
            'status' => 'success',
            'message' => 'Eintrag wurde erfolgreich aktualisiert.',
            'data' => null,
        ]);
    }
}
