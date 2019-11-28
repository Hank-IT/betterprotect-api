<?php

namespace App\Services\PostfixPolicyInstallation;

use App\Support\IPv4;
use App\Models\ClientSenderAccess;

class ClientAccessHandler extends AbstractHandler
{
    /**
     * @throws \Exception
     */
    public function install()
    {
        $this->task->update(['message' => 'Blacklist/Whitelist wird aktualisiert...']);

        $clientAccessRows = $this->getClientAccessRows();

        $clientAccessIps = $this->getClientAccessIPs();

        $this->insert(array_merge($clientAccessRows, $clientAccessIps));
    }

    protected function table()
    {
        return 'client_access';
    }

    protected function getClientAccessIPs()
    {
        // Generate client access ip network range
        $clientAccessNets = ClientSenderAccess::where('active', '=', 1)->where('type', '=', 'client_ipv4_net')->get();

        return $this->calculateClientAccessIPsForNetworks($clientAccessNets);
    }

    /**
     * @return mixed
     */
    protected function getClientAccessRows()
    {
        $clientAccess = ClientSenderAccess::where('active', '=', 1)->whereIn('type', ['client_hostname', 'client_ipv4'])->get();

        return $clientAccess->map(function ($row) {
            return collect($row->toArray())
                ->only(['payload', 'action'])
                ->all();
        })->toArray();
    }

    /**
     * @param $clientAccessNets
     * @return array
     */
    protected function calculateClientAccessIPsForNetworks($clientAccessNets)
    {
        $clientAccessIps = [];
        foreach ($clientAccessNets as $key => $clientAccessNet) {

            $clientAccessIps[$key] = IPv4::cidr2range($clientAccessNet->payload);

            foreach($clientAccessIps[$key] as $index => $data) {
                $clientAccessIps[$index]['payload'] = $data;
                $clientAccessIps[$index]['action'] = $clientAccessNet->action;
                unset($clientAccessIps[$key]);
            }
        }

        return array_values($clientAccessIps);
    }
}
