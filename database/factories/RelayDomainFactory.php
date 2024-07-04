<?php

namespace Database\Factories;

use App\Services\Recipients\Models\RelayRecipient;
use App\Services\RelayDomains\Models\RelayDomain;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Services\Recipients\Models\RelayRecipient>
 */
class RelayDomainFactory extends Factory
{
    protected $model = RelayDomain::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'domain' => fake()->unique()->domainName,
        ];
    }
}
