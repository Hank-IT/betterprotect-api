<?php

namespace App\Services\Transport\Actions;

use App\Services\Transport\Models\Transport;

class CreateTransport
{
    public function execute(
        string $domain,
        ?string $transport,
        ?string $nexthopType,
        ?string $nexthop,
        ?int $nexthopPort,
        ?bool $nexthopMx,
        string $dataSource,
    ): Transport {
        return Transport::create([
            'domain' => $domain,
            'transport' => $transport,
            'nexthop' => $nexthop,
            'nexthop_port' => $nexthopPort,
            'nexthop_type' => $nexthopType,
            'nexthop_mx' => $nexthopMx,
            'data_source' => $dataSource,
        ]);
    }
}
