<?php

namespace App\Services\BetterprotectPolicy\Actions;

use App\Services\BetterprotectPolicy\Contracts\BetterprotectPolicyDataRetriever;
use App\Services\BetterprotectPolicy\Repositories\TransportMapRepository;

class GetTransportMaps implements BetterprotectPolicyDataRetriever
{
    public function __construct(protected TransportMapRepository $transportMapRepository) {}

    public function execute(): array
    {
        // ToDo: Empty nexthop

        return $this->transportMapRepository->get()->map(function ($row) {
            if ($row->nexthop_type == 'ipv4' || $row->nexthop_type == 'ipv6') {
                $nexthop = '[' . $row->nexthop . ']';
            } else {
                $nexthop = $row->nexthop_mx
                    ? $row->nexthop
                    : '[' . $row->nexthop . ']';
            }

            return collect([
                'domain' => $row->domain,
                'payload' => $row->transport . ':' . $nexthop . ':' . $row->nexthop_port,
            ]);
        })->toArray();
    }
}
