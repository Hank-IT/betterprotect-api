<?php

namespace Tests\Feature\Services\BetterprotectPolicy\Actions;

use App\Services\BetterprotectPolicy\Actions\GetTransportMaps;
use App\Services\BetterprotectPolicy\Repositories\TransportMapRepository;
use App\Services\Transport\Models\Transport;
use Illuminate\Database\Eloquent\Collection;
use Mockery\MockInterface;
use Tests\TestCase;

class GetTransportMapsTest extends TestCase
{
    public function test()
    {
        $transportMapIpv4 = Transport::factory()->create([
            'nexthop_type' => 'ipv4',
            'nexthop' => fake()->ipv4,
        ]);

        $transportMapIpv6 = Transport::factory()->create([
            'nexthop_type' => 'ipv6',
            'nexthop' => fake()->ipv6,
        ]);

        $transportHostnameWithMx = Transport::factory()->create([
            'nexthop_type' => 'hostname',
            'nexthop' => fake()->domainName,
            'nexthop_mx' => true,
        ]);

        $transportHostnameWithoutMx = Transport::factory()->create([
            'nexthop_type' => 'hostname',
            'nexthop' => fake()->domainName,
            'nexthop_mx' => false,
        ]);

        $this->mock(TransportMapRepository::class, function(MockInterface $mock) use($transportMapIpv4, $transportMapIpv6, $transportHostnameWithMx, $transportHostnameWithoutMx) {
            $mock->shouldReceive('get')->once()->andReturn(new Collection([$transportMapIpv4, $transportMapIpv6, $transportHostnameWithMx, $transportHostnameWithoutMx]));
        });

        $data = app(GetTransportMaps::class)->execute();

        $this->assertEquals($transportMapIpv4->domain, $data[0]['domain']);
        $this->assertEquals(sprintf('%s:[%s]:%s', $transportMapIpv4->transport, $transportMapIpv4->nexthop, $transportMapIpv4->nexthop_port), $data[0]['payload']);

        $this->assertEquals($transportMapIpv6->domain, $data[1]['domain']);
        $this->assertEquals(sprintf('%s:[%s]:%s', $transportMapIpv6->transport, $transportMapIpv6->nexthop, $transportMapIpv6->nexthop_port), $data[1]['payload']);

        $this->assertEquals($transportHostnameWithMx->domain, $data[2]['domain']);
        $this->assertEquals(sprintf('%s:%s:%s', $transportHostnameWithMx->transport, $transportHostnameWithMx->nexthop, $transportHostnameWithMx->nexthop_port), $data[2]['payload']);

        $this->assertEquals($transportHostnameWithoutMx->domain, $data[3]['domain']);
        $this->assertEquals(sprintf('%s:[%s]:%s', $transportHostnameWithoutMx->transport, $transportHostnameWithoutMx->nexthop, $transportHostnameWithoutMx->nexthop_port), $data[3]['payload']);

    }
}
