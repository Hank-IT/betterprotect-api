<?php

namespace Database\Factories;

use App\Services\Recipients\Models\RelayRecipient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Services\Recipients\Models\RelayRecipient>
 */
class RelayRecipientFactory extends Factory
{
    protected $model = RelayRecipient::class;

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
