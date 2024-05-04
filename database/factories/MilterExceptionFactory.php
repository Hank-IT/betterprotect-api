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
        return [
            'id' => $this->getKey(),
            'client_type' => $this->client_type,
            'client_payload' => $this->client_payload,
            'description' => $this->description,
            'active' => fake()->numberBetween(0, 1),
        ];
    }
}
