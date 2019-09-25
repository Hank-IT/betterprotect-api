<?php

namespace App\Services\PolicyInstallation;

use App\Models\RelayRecipient;

class RecipientAccessHandler extends AbstractHandler
{
    /**
     * @throws \Exception
     */
    public function install()
    {
        $this->task->update(['message' => 'Empfänger werden aktualisiert...']);

        $this->insert($this->getRecipientAccessRows());
    }

    protected function table()
    {
        return 'relay_recipients';
    }

    protected function getRecipientAccessRows()
    {
        $recipientAccess = RelayRecipient::all();

        return $recipientAccess->map(function ($row) {
            return collect($row->toArray())
                ->only(['payload', 'action'])
                ->all();
        })->toArray();
    }
}
