<?php

namespace Tests\Feature\Services\Transport\Commands;

use App\Services\Transport\Actions\CreateTransport;
use App\Services\Transport\Models\Transport;
use Mockery\MockInterface;
use Tests\TestCase;

class StoreTransportRuleTest extends TestCase
{
    public function test_success()
    {
        $transport = Transport::factory()->make();

        $this->mock(CreateTransport::class, function(MockInterface $mock) use($transport) {
            $mock->shouldReceive('execute')->once()->withArgs([
                $transport->domain,
                $transport->transport,
                $transport->nexthop_type,
                $transport->nexthop,
                $transport->nexthop_port,
                $transport->nexthop_mx,
                $transport->data_source,
            ])->andReturn($transport);
        });

        $this->artisan('transport:store', [
            'domain' => $transport->domain,
            '--transport' => $transport->transport,
            '--nexthop' => $transport->nexthop,
            '--nexthop_type' => $transport->nexthop_type,
            '--nexthop_mx' => $transport->nexthop_mx,
            '--nexthop_port' => $transport->nexthop_port,
            '--data_source' => $transport->data_source,
        ])->assertSuccessful();
    }

    public function test_validation_error()
    {
        $transport = Transport::factory()->make();

        $this->mock(CreateTransport::class, function(MockInterface $mock) use($transport) {
            $mock->shouldReceive('execute')->never();
        });

        $this->artisan('transport:store', [
            'domain' => $transport->domain,
            '--transport' => $transport->transport,
            '--nexthop' => $transport->nexthop,
            '--nexthop_type' => $transport->nexthop_type,
            '--nexthop_mx' => $transport->nexthop_mx,
            '--nexthop_port' => 'test',
            '--data_source' => $transport->data_source,
        ])->assertFailed();
    }
}
