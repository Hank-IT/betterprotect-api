<?php

namespace App\Http\Controllers;

use App\Services\Order\Actions\OrderItems;
use App\Services\Rules\Models\ClientSenderAccess;

class AccessPriorityController extends Controller
{
    public function moveUp(ClientSenderAccess $clientSenderAccess)
    {
        app(OrderItems::class, ['model' => $clientSenderAccess])->moveUp();

        return response()->json([
            'status' => 'success',
            'message' => 'Eintrag wurde erfolgreich aktualisiert.',
            'data' => null,
        ]);
    }

    public function moveDown(ClientSenderAccess $clientSenderAccess)
    {
        app(OrderItems::class, ['model' => $clientSenderAccess])->moveDown();

        return response()->json([
            'status' => 'success',
            'message' => 'Eintrag wurde erfolgreich aktualisiert.',
            'data' => null,
        ]);
    }
}
