<?php

namespace Database\Factories;

use App\Services\Milter\Models\MilterException;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Services\Recipients\Models\RelayRecipient>
 */
class MilterExceptionFactory extends Factory
{
    protected $model = MilterException::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $clientTypes = ['client_ipv4', 'client_ipv6', 'client_ipv4_net'];

        return [
            'client_type' => $clientType = $clientTypes[array_rand($clientTypes)],
            'client_payload' => $this->getPayloadForType($clientType),
            'description' => fake()->text,
            'active' => fake()->numberBetween(0, 1),
        ];
    }

    protected function getPayloadForType(string $clientType): string
    {
        switch ($clientType) {
            case 'client_ipv4':
                return fake()->ipv4;
            case 'client_ipv6':
                return fake()->ipv6;
            case 'client_ipv4_net':
                return '192.168.0.0/24';
        }
    }
}
