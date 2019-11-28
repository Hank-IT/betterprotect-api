<?php

namespace App\Services\PostfixPolicyInstallation;

use App\Models\ClientSenderAccess;

class SenderAccessHandler extends AbstractHandler
{
    /**
     * @throws \Exception
     */
    public function install()
    {
        $this->task->update(['message' => 'Blacklist/Whitelist wird aktualisiert...']);

        $this->insert($this->getSenderAccessRows());
    }

    protected function table()
    {
        return 'sender_access';
    }

    protected function getSenderAccessRows()
    {
        $senderAccess = ClientSenderAccess::where('active', '=', 1)->whereIn('type', ['mail_from_address', 'mail_from_domain', 'mail_from_localpart'])->get();

        return $senderAccess->map(function ($row) {
            return collect($row->toArray())
                ->only(['payload', 'action'])
                ->all();
        })->toArray();
    }
}
