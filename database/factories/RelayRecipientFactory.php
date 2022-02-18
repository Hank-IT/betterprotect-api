<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RelayRecipient>
 */
class RelayRecipientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'data_source' => 'local',
            'action' => 'OK',
            'payload' => $this->faker->unique()->safeEmail(),
            'active' => true,
        ];
    }
}
