<?php

namespace App\Http\Controllers;

use App\Models\MilterException;
use App\Services\Orderer;

class MilterExceptionPriorityController extends Controller
{
    public function moveUp(MilterException $exception)
    {
        app(Orderer::class, ['model' => $exception])->moveUp();

        return response()->json([
            'status' => 'success',
            'message' => 'Eintrag wurde erfolgreich aktualisiert.',
            'data' => null,
        ]);
    }

    public function moveDown(MilterException $exception)
    {
        app(Orderer::class, ['model' => $exception])->moveDown();

        return response()->json([
            'status' => 'success',
            'message' => 'Eintrag wurde erfolgreich aktualisiert.',
            'data' => null,
        ]);
    }
}
