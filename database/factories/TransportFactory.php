<?php

namespace Database\Factories;

use App\Services\Transport\Models\Transport;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransportFactory extends Factory
{
    protected $model = Transport::class;

    public function definition()
    {
        return [
            'domain' => fake()->domainName,
            'transport' => 'smtp',
            'nexthop' => fake()->ipv4,
            'nexthop_type' => null,
            'nexthop_port' => 25,
            'nexthop_mx' => false,
            'data_source' => 'local',
        ];
    }
}
