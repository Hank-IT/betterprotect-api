<?php

namespace Tests\Feature\Controllers\API\Policy;

use App\Services\Authentication\Models\User;
use App\Services\Transport\Actions\CreateTransport;
use App\Services\Transport\Actions\PaginateTransports;
use App\Services\Transport\Models\Transport;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery\MockInterface;
use Tests\TestCase;

class TransportControllerTest extends TestCase
{
    public function testIndex()
    {
        $user = User::factory()->create();

        $this->be($user);

        $transport = Transport::factory()->create();

        $paginator = new LengthAwarePaginator([
            $transport
        ], 1, 5);

        $this->mock(PaginateTransports::class, function(MockInterface $mock) use($paginator) {
            $mock->shouldReceive('execute')->once()->withArgs([
                1, 5, 'test',
            ])->andReturn($paginator);
        });

        $this->getJson(route('api.v1.transports.index', [
            'page_number' => 1,
            'page_size' => 5,
            'search' => 'test'
        ]))->assertSuccessful()->assertJsonPath('data.0.id', $transport->getKey());
    }

    public function testStore()
    {
        $user = User::factory()->create();

        $this->be($user);

        $transport = Transport::factory()->create();

        $data = Transport::factory()->make();

        $this->mock(CreateTransport::class, function(MockInterface $mock) use($data, $transport) {
            $mock->shouldReceive('execute')->once()->withArgs([
                $data->domain,
                $data->transport,
                $data->nexthop_type,
                $data->nexthop,
                $data->nexthop_port,
                $data->nexthop_mx,
                'local',
            ])->andReturn($transport);
        });

        $this->postJson(route('api.v1.transports.store'), [
            'domain' => $data->domain,
            'transport' => $data->transport,
            'nexthop_type' => $data->nexthop_type,
            'nexthop' => $data->nexthop,
            'nexthop_port' => $data->nexthop_port,
            'nexthop_mx' => $data->nexthop_mx,
        ])->assertSuccessful()->assertJsonPath('data.id', $transport->getKey());
    }

    public function testDestroy()
    {
        $user = User::factory()->create();

        $this->be($user);

        $deletableTransport1 = Transport::factory()->create();
        $deletableTransport2 = Transport::factory()->create();
        $retainedTransport = Transport::factory()->create();

        $this->deleteJson(route('api.v1.transports.destroy'), [
            'ids' => [$deletableTransport1->getKey(), $deletableTransport2->getKey()],
        ]);

        $this->assertModelMissing($deletableTransport1);
        $this->assertModelMissing($deletableTransport2);
        $this->assertModelExists($retainedTransport);
    }
}
