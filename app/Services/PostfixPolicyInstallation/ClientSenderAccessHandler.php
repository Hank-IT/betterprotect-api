<?php

namespace App\Services\PostfixPolicyInstallation;

use  App\Models\ClientSenderAccess as ClientSenderAccessModel;

class ClientSenderAccessHandler extends AbstractHandler
{
    public function install()
    {
        $this->task->update(['message' => 'Blacklist/Whitelist wird aktualisiert...']);

        $this->insert($this->getClientSenderAccessRows());
    }

    protected function table()
    {
        return 'client_sender_access';
    }

    protected function getClientSenderAccessRows()
    {
        $clientSenderAccess = ClientSenderAccessModel::where('active', '=', 1)->get();

        return $clientSenderAccess->map(function ($row) {
            return collect($row->toArray())
                ->only(['client_type', 'client_payload', 'sender_type', 'sender_payload', 'action'])
                ->all();
        })->toArray();
    }
}