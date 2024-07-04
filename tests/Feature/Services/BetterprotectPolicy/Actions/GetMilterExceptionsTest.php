<?php

namespace Tests\Feature\Services\BetterprotectPolicy\Actions;

use App\Services\Milter\Models\Milter;
use Exception;
use App\Services\BetterprotectPolicy\Actions\GetMilterExceptions;
use App\Services\BetterprotectPolicy\Repositories\MilterExceptionRepository;
use App\Services\Milter\Models\MilterException;
use Illuminate\Database\Eloquent\Collection;
use Mockery\MockInterface;
use Tests\TestCase;

class GetMilterExceptionsTest extends TestCase
{
    public function test_without_milter()
    {
        $clientIpv4Exception = MilterException::factory()->create([
            'client_type' => 'client_ipv4',
            'client_payload' => fake()->ipv4,
        ]);

        $clientIpv6Exception = MilterException::factory()->create([
            'client_type' => 'client_ipv6',
            'client_payload' => fake()->ipv6,
        ]);

        $clientIpv4NetException = MilterException::factory()->create([
            'client_type' => 'client_ipv4_net',
            'client_payload' => '192.168.0.0/30',
        ]);

        $this->mock(MilterExceptionRepository::class, function (MockInterface $mock) use ($clientIpv4Exception, $clientIpv6Exception, $clientIpv4NetException) {
            $mock->shouldReceive('get')->once()->andReturn(new Collection([
                $clientIpv4Exception, $clientIpv6Exception, $clientIpv4NetException
            ]));
        });

        $data = app(GetMilterExceptions::class)->execute();

        $this->assertCount(6, $data);

        $this->assertEquals($clientIpv4Exception->client_payload, $data[0]['payload']);
        $this->assertEquals('DISABLE', $data[0]['definition']);
        $this->assertEquals($clientIpv4Exception->priority, $data[0]['priority']);

        $this->assertEquals($clientIpv6Exception->client_payload, $data[1]['payload']);
        $this->assertEquals('DISABLE', $data[1]['definition']);
        $this->assertEquals($clientIpv6Exception->priority, $data[1]['priority']);

        $this->assertEquals('192.168.0.0', $data[2]['payload']);
        $this->assertEquals('DISABLE', $data[2]['definition']);
        $this->assertEquals($clientIpv4NetException->priority, $data[2]['priority']);
        $this->assertEquals('192.168.0.1', $data[3]['payload']);
        $this->assertEquals('DISABLE', $data[3]['definition']);
        $this->assertEquals($clientIpv4NetException->priority, $data[3]['priority']);
        $this->assertEquals('192.168.0.2', $data[4]['payload']);
        $this->assertEquals('DISABLE', $data[4]['definition']);
        $this->assertEquals($clientIpv4NetException->priority, $data[4]['priority']);
        $this->assertEquals('192.168.0.3', $data[5]['payload']);
        $this->assertEquals('DISABLE', $data[5]['definition']);
        $this->assertEquals($clientIpv4NetException->priority, $data[5]['priority']);
    }

    public function test_with_milter()
    {
        $milter = Milter::factory()->create();

        $clientIpv4Exception = MilterException::factory()->hasAttached($milter)->create([
            'client_type' => 'client_ipv4',
            'client_payload' => fake()->ipv4,
        ]);

        $clientIpv6Exception = MilterException::factory()->hasAttached($milter)->create([
            'client_type' => 'client_ipv6',
            'client_payload' => fake()->ipv6,
        ]);

        $clientIpv4NetException = MilterException::factory()->hasAttached($milter)->create([
            'client_type' => 'client_ipv4_net',
            'client_payload' => '192.168.0.0/30',
        ]);

        $this->mock(MilterExceptionRepository::class, function (MockInterface $mock) use ($clientIpv4Exception, $clientIpv6Exception, $clientIpv4NetException) {
            $mock->shouldReceive('get')->once()->andReturn(new Collection([
                $clientIpv4Exception, $clientIpv6Exception, $clientIpv4NetException
            ]));
        });

        $data = app(GetMilterExceptions::class)->execute();

        $this->assertCount(6, $data);

        $this->assertEquals($clientIpv4Exception->client_payload, $data[0]['payload']);
        $this->assertEquals($milter->definition, $data[0]['definition']);
        $this->assertEquals($clientIpv4Exception->priority, $data[0]['priority']);

        $this->assertEquals($clientIpv6Exception->client_payload, $data[1]['payload']);
        $this->assertEquals($milter->definition, $data[1]['definition']);
        $this->assertEquals($clientIpv6Exception->priority, $data[1]['priority']);

        $this->assertEquals('192.168.0.0', $data[2]['payload']);
        $this->assertEquals($milter->definition, $data[2]['definition']);
        $this->assertEquals($clientIpv4NetException->priority, $data[2]['priority']);
        $this->assertEquals('192.168.0.1', $data[3]['payload']);
        $this->assertEquals($milter->definition, $data[3]['definition']);
        $this->assertEquals($clientIpv4NetException->priority, $data[3]['priority']);
        $this->assertEquals('192.168.0.2', $data[4]['payload']);
        $this->assertEquals($milter->definition, $data[4]['definition']);
        $this->assertEquals($clientIpv4NetException->priority, $data[4]['priority']);
        $this->assertEquals('192.168.0.3', $data[5]['payload']);
        $this->assertEquals($milter->definition, $data[5]['definition']);
        $this->assertEquals($clientIpv4NetException->priority, $data[5]['priority']);
    }

    public function test_invalid_client_type()
    {
        $this->expectException(Exception::class);

        $invalid = MilterException::factory()->create([
            'client_type' => 'client_ipv5',
            'client_payload' => fake()->ipv4,
        ]);

        $this->mock(MilterExceptionRepository::class, function (MockInterface $mock) use ($invalid) {
            $mock->shouldReceive('get')->once()->andReturn(new Collection([
                $invalid
            ]));
        });

        app(GetMilterExceptions::class)->execute();
    }
}
