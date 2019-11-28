<?php

namespace App\Services\PostfixPolicyInstallation;

use App\Models\RelayDomain;

class RelayDomainHandler extends AbstractHandler
{
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
        return RelayDomain::where('active', '=', 1)->get('domain')->toArray();
    }
}
