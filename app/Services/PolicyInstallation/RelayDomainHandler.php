<?php

namespace App\Services\PolicyInstallation;

use App\Models\RelayDomain;

class RelayDomainHandler extends AbstractHandler
{
    const CONNECTION = 'postfix_db';

    /**
     * @throws \Exception
     */
    public function install()
    {
        $this->task->update(['message' => 'Relay Domänen werden aktualisiert...']);

        $this->insert($this->getRelayDomainRows());
    }

    protected function table()
    {
        return 'relay_domains';
    }

    protected function getRelayDomainRows()
    {
        return RelayDomain::all('domain')->toArray();
    }
}
