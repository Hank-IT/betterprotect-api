<?php

namespace App\Http\Controllers;

use App\Models\ClientSenderAccess;
use App\Services\AccessOrder;

class AccessPriorityController extends Controller
{
    public function moveUp(ClientSenderAccess $clientSenderAccess)
    {
        app(AccessOrder::class, ['clientSenderAccess' => $clientSenderAccess])->moveUp();

        return response()->json([
            'status' => 'success',
            'message' => 'Eintrag wurde erfolgreich aktualisiert.',
            'data' => null,
        ]);
    }

    public function moveDown(ClientSenderAccess $clientSenderAccess)
    {
        app(AccessOrder::class, ['clientSenderAccess' => $clientSenderAccess])->moveDown();

        return response()->json([
            'status' => 'success',
            'message' => 'Eintrag wurde erfolgreich aktualisiert.',
            'data' => null,
        ]);
    }
}
