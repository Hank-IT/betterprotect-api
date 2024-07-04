<?php

namespace App\Services\Rules\Actions;

use App\Services\Rules\Models\ClientSenderAccess;

class DeleteClientSenderAccess
{
    public function execute(ClientSenderAccess $clientSenderAccess): ?bool
    {
        return $clientSenderAccess->delete();
    }
}
