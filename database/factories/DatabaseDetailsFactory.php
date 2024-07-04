<?php

namespace Database\Factories;

use App\Services\Server\dtos\DatabaseDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

class DatabaseDetailsFactory extends Factory
{
    protected $model = DatabaseDetails::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'hostname' => fake()->domainName,
            'database' => fake()->name,
            'username' => fake()->username,
            'password' => encrypt(fake()->password),
            'port' => fake()->numberBetween(1, 65535),
        ];
    }
}
