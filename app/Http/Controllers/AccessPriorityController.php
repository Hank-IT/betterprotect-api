<?php

namespace App\Http\Controllers;

use App\Services\Orderer;
use App\Models\ClientSenderAccess;

class AccessPriorityController extends Controller
{
    public function moveUp(ClientSenderAccess $clientSenderAccess)
    {
        app(Orderer::class, ['model' => $clientSenderAccess])->moveUp();

        return response()->json([
            'status' => 'success',
            'message' => 'Eintrag wurde erfolgreich aktualisiert.',
            'data' => null,
        ]);
    }

    public function moveDown(ClientSenderAccess $clientSenderAccess)
    {
        app(Orderer::class, ['model' => $clientSenderAccess])->moveDown();

        return response()->json([
            'status' => 'success',
            'message' => 'Eintrag wurde erfolgreich aktualisiert.',
            'data' => null,
        ]);
    }
}
