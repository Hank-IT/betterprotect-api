<?php

namespace Tests\Feature\Services\Transport\Actions;

use App\Services\Transport\Actions\CreateTransport;
use App\Services\Transport\Models\Transport;
use Tests\TestCase;

class CreateTransportTest extends TestCase
{
    public function test()
    {
        $data = Transport::factory()->make();

        $model = app(CreateTransport::class)->execute(
            $data->domain,
            $data->transport,
            $data->nexthop_type,
            $data->nexthop,
            $data->nexthop_port,
            $data->nexthop_mx,
            $data->data_source,
        );

        $this->assertModelExists($model);

        $this->assertDatabaseHas('transports', [
            'domain' => $data->domain,
            'transport' => $data->transport,
            'nexthop_type' => $data->nexthop_type,
            'nexthop' => $data->nexthop,
            'nexthop_port' => $data->nexthop_port,
            'nexthop_mx' => $data->nexthop_mx,
            'data_source' => $data->data_source,
        ]);
    }
}
