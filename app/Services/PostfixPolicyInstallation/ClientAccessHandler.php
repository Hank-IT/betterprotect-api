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

    /**
     * ToDo
     */
    protected function getClientAccessIPs()
    {
        // Generate client access ip network range
        $clientAccessNets = ClientSenderAccess::where('active', '=', 1)->whereNotNull('client')->where('type', '=', 'client_ipv4_net')->get();

        return $this->calculateClientAccessIPsForNetworks($clientAccessNets);
    }

    /**
     * @return mixed
     */
    protected function getClientAccessRows()
    {
        $clientAccess = ClientSenderAccess::where('active', '=', 1)->whereNotNull('client')->get();

        return $clientAccess->map(function ($row) {
            $row = $row->toArray();

            // Combination Rule
            if (! empty($row['sender'])) {
                // Add configured restriction class as action.
                $row['action'] = config('postfix.client_sender_combination_restriction_class');
            }

            // Change payload to client for consistent naming.
            $row['payload'] = $row['client'];

            return collect($row)
                ->only(['payload', 'action', 'client_access_combination'])
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
