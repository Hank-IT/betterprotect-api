<?php

namespace App\Services\Rules\Actions;

use App\Services\Rules\Models\ClientSenderAccess;
use Illuminate\Database\Eloquent\Collection;

class GetClientSenderAccess
{
    public function execute(?string $search = null): Collection
    {
        $query = ClientSenderAccess::query();

        if ($search) {
            $query->where('client_payload', 'LIKE', '%' . $search . '%')
                ->orWhere('sender_payload', 'LIKE', '%' . $search . '%');
        }

        $query->orderBy('priority');

        return $query->get();
    }
}
