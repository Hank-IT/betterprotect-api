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
        $senderAccess = ClientSenderAccess::where('active', '=', 1)->whereNotNull('sender')->get();

        return $senderAccess->map(function ($row) {
            $row = $row->toArray();

            // Combination Rule
            if (! empty($row['sender'])) {
                // Define this rule as combination rule, so that only
                // combined checks of client and sender may match.
                $row['client_access_combination'] = 1;
            }

            return collect($row)
                ->only(['payload', 'action'])
                ->all();
        })->toArray();
    }
}
