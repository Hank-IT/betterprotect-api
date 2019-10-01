<?php

namespace App\Services\PolicyInstallation;

use App\Models\Transport;

class TransportMapHandler extends AbstractHandler
{
    const CONNECTION = 'postfix_db';

    /**
     * @throws \Exception
     */
    public function install()
    {
        $this->task->update(['message' => 'Transport Einträge werden aktualisiert...']);

        $this->insert($this->getTransportRows());
    }

    protected function table()
    {
        return 'transport_maps';
    }

    protected function getTransportRows()
    {
        $transportMaps = Transport::all();

        // ToDo: Empty nexthop

        return $transportMaps->map(function ($row) {
            if ($row->nexthop_type == 'ipv4' || $row->nexthop_type == 'ipv6') {
                $nexthop = '[' . $row->nexthop . ']';
            } else {
                if ($row->nexthop_mx) {
                    $nexthop = $row->nexthop;
                } else {
                    $nexthop = '[' . $row->nexthop . ']';
                }
            }

            return collect([
                'domain' => $row->domain,
                'payload' => $row->transport . ':' . $nexthop . ':' . $row->nexthop_port,
            ]);
        })->toArray();
    }
}
